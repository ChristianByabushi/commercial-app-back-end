<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class line_InvoiceModel extends Model
{
	protected $db;
	public $session;
	public $tableinvoice;

	public function __construct(ConnectionInterface $db)
	{
		$this->db = $db;
		$this->tablelineinvoice = $this->db->table('lines_invoice');
	}

	public function addlineinvoice($array = [])
    {
        return $this->tablelineinvoice->insert($array);
    }

	//get the id of the next lines_invoice
	public function getLastId()
	{
		$query = "SELECT MAX(id_line) as id_line FROM lines_invoice";
		return $this->db->query($query)->getRow();
	}

	// useful when we want to get each invoice's client 
	public function getAllInvoices()
	{
		$query = "SELECT lines_invoice.client, lines_invoice.id_line, lines_invoice.created_at, 
		sum(invoice.pu_sale * invoice.amount_sale) AS totalprice, lines_invoice.decrease,
		sum(invoice.pu_sale* invoice.amount_sale) - lines_invoice.decrease 
		AS net_total_price 
		FROM invoice,
		lines_invoice WHERE (lines_invoice.deleted = 0 AND invoice.deleted = 0 
		AND lines_invoice.id_line = invoice.id_line)
		GROUP BY lines_invoice.id_line 
		ORDER BY lines_invoice.id_line DESC";
		return $this->db->query($query)->getResult();
	}

	//when we want to delete invoice, we consult either linetableinvoice,and invoice
	public function deleteInvoice($id = null)
	{
		$this->tablelineinvoice->where('id_line', $id)->update(array("deleted" => 1));
		$this->db->table('invoice')->where('id_line', $id)->update(array("deleted" => 1));
		return true;
	}

	// useful for the print
	public function getInvoiceInfo($id)
	{
		return $this->db->table('invoice')->select("title,pu_sale,amount_sale, (pu_sale * amount_sale) as pt ")
			->join('stock_merchandise', 'invoice.id_stock = stock_merchandise.id_stock')
			->join('merchandise', 'merchandise.id = stock_merchandise.id_merchandise')
			->getWhere(array(
				'stock_merchandise.deleted' => 0,
				'invoice.deleted' => 0,
				'merchandise.deleted' => 0,
				"invoice.id_line" => $id
			))
			->getResult();
	}

	//get only the inline invoice
	public function getInlineInfo($id = null)
	{
		return $this->tablelineinvoice->select("*")->getwhere(array('id_line' => $id, "deleted"=>0))->getRow();
	}
}
