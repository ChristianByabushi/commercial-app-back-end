<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class resultModel extends Model
{
	protected $db;
	public $session;
	public $tableinvoice;

	public function __construct(ConnectionInterface $db)
	{
		$this->db = $db;
		$this->tableinvoice = $this->db->table('invoice');
	}
	// we gonna considerate this function for each line not lines, related to each item!
	public function getResultInvoices($date = null, $itemId = null)
	{
		$query1 = "
		SELECT invoice.id_line,lines_invoice.created_at, merchandise.title,
		lines_invoice.client, stock_merchandise.pu_by,		
        invoice.pu_sale, invoice.amount_sale, 
        ((stock_merchandise.pu_by * invoice.amount_sale)) as price_by,
		(invoice.pu_sale * invoice.amount_sale) as price_sale,		
		((invoice.pu_sale * invoice.amount_sale) -( stock_merchandise.pu_by * invoice.amount_sale)) as result";

		$query2 = "SELECT  
        SUM((stock_merchandise.pu_by * invoice.amount_sale)) as price_by,
		SUM(invoice.pu_sale * invoice.amount_sale) as price_sale,		
		SUM((invoice.pu_sale * invoice.amount_sale) -( stock_merchandise.pu_by * invoice.amount_sale)) as result";

		$part2 = " FROM lines_invoice, stock_merchandise, merchandise, invoice
		where( 
		invoice.deleted = 0 and
		lines_invoice.deleted = 0 and
		lines_invoice.id_line = invoice.id_line and  
		invoice.id_stock = stock_merchandise.id_stock  and 
		merchandise.id = stock_merchandise.id_merchandise ";

		$query1 .= $part2;
		$query2 .=  $part2;

		if ($date != null) {
			$query1 .= " and  lines_invoice.created_at >= '$date' ";
			$query2 .= " and  lines_invoice.created_at >= '$date' ";
		}

		if ($itemId != null) {
			$query1 .= " and stock_merchandise.id_merchandise = $itemId ";
			$query2 .= " and stock_merchandise.id_merchandise = $itemId ";
		}
		$query1 .= " ) ORDER BY lines_invoice.id_line DESC";
		$query2 .= " )";

		$result1 = $this->db->query($query1)->getResult();
		$result2 = $this->db->query($query2)->getRow();
		return array($result1, $result2);
	}

	// generally want to find the result found from each lineInvoice related to the purchase of the each client
	public function getResultLinesInvoice($date = null)
	{
		$query = "SELECT invoice.id_invoice,lines_invoice.created_at,  lines_invoice.client, 
		SUM(invoice.pu_sale * invoice.amount_sale) as 
		price_sale, 
		SUM(stock_merchandise.pu_by * stock_merchandise.amount_by) as price_by, SUM(lines_invoice.decrease) as total, 
		(SUM(invoice.pu_sale * invoice.amount_sale) - SUM(stock_merchandise.pu_by * stock_merchandise.amount_by) 
		- SUM(lines_invoice.decrease)) 
		as result FROM lines_invoice, stock_merchandise, invoice
		where(invoice.deleted = 0 and
		lines_invoice.deleted = 0 and
		 lines_invoice.id_line = invoice.id_line and 
		 invoice.id_stock = stock_merchandise.id_stock";
		if ($date != null)
			$query .= " and lines_invoice.created_at >= '$date')";
		else
			$query .= " )";

		$query .= " GROUP BY invoice.id_line ";

		return $this->db->query($query)->getResult();
	}

	public function totalAmountby($date = null)
	{
		$query = "SELECT  
        SUM(stock_merchandise.amount_by) as totalAmountBought
		FROM  stock_merchandise, merchandise
		where( 
		merchandise.id = stock_merchandise.id_merchandise and
		stock_merchandise.deleted = 0 and 
		merchandise.deleted = 0 AND
        stock_merchandise.created_at >= '$date'
		)";
		return $this->db->query($query)->getRow();
	}

	public function totalAmountSold($date = null)
	{
		$query = "SELECT  
        SUM(invoice.amount_sale) as totalAmountSold
		FROM  invoice, lines_invoice
		where( 
			invoice.deleted = 0 and 
			lines_invoice.id_line = invoice.id_line and 
			lines_invoice.created_at >= '$date'
		)";
		return $this->db->query($query)->getRow();
	}

	public function highSoldAchieved($date = null)
	{
		$query = "SELECT 		 		
		MAX((invoice.pu_sale * invoice.amount_sale) - (stock_merchandise.pu_by * invoice.amount_sale) -  lines_invoice.decrease)  
		as result
		FROM lines_invoice, stock_merchandise, merchandise, invoice where(
		invoice.deleted = 0 and
		lines_invoice.deleted = 0 and
		lines_invoice.id_line = invoice.id_line and  
		invoice.id_stock = stock_merchandise.id_stock  and 
		merchandise.id = stock_merchandise.id_merchandise and 
		lines_invoice.created_at >= '$date'
		)";
		return	$this->db->query($query)->getRow();
	}

	public function DetailsItems()
	{
		$query = "SELECT id, title, categorie, description From merchandise where(deleted=0) group by title";
		return $this->db->query($query)->getResult();
	}
}
