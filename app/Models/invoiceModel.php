<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class invoiceModel extends Model
{
	protected $db;
	public $session;
	public $tableinvoice;

	public function __construct(ConnectionInterface $db)
	{
		$this->db = $db;
		$this->tableinvoice = $this->db->table('invoice');
	}

	public function addinvoice($array = [])
	{
		return $this->tableinvoice->insert($array);
	}

	public function editInvoice($array = [], $id = ' ')
	{
		$this->tableStock->where('id', $id)->update($array);
	}

	public function showRecord($id)
	{
		return $this->tableinvoice->select("*")
			->join('stock_merchandise', 'invoice.id_stock = stock_merchandise.id_stock')
			->join('merchandise', 'merchandise.id = stock_merchandise.id_merchandise')
			->getWhere(array(
				'stock_merchandise.deleted' => 0,
				'invoice.deleted' => 0,
				'merchandise.deleted' => 0,
				"invoice.id_invoice" => $id
			))
			->getResult();
	}


	///verify 

	public function verifyAmount($id)
	{
		$query = "SELECT (SELECT SUM(stock_merchandise.amount_by) 
		FROM stock_merchandise 
		WHERE stock_merchandise.id_stock = $id) as stock_bought, invoice.id_stock as id_stock, 
		SUM(invoice.amount_sale) as stock_sold FROM stock_merchandise, invoice
		WHERE (invoice.id_stock = stock_merchandise.id_stock
		and invoice.id_stock = $id
		)
		";
		$data = $this->db->query($query)->getRow();
		$amount_remaining = (float) $data->stock_bought - (float) $data->stock_sold;
		return $amount_remaining;
	}

	public function deleteRecord($id)
	{
		return	$this->tableinvoice->where('id_invoice', $id)->update(array("deleted" => 1));
	}

	public function  getAllRecords()
	{
		return $this->tableinvoice->select("*")
			->join('stock_merchandise', 'invoice.id_stock = stock_merchandise.id_stock')
			->join('merchandise', 'merchandise.id = stock_merchandise.id_merchandise')
			->getWhere(array(
				'stock_merchandise.deleted' => 0,
				'invoice.deleted' => 0,
				'merchandise.deleted' => 0,
			))
			->getResult();
	}
}
