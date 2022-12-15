<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class stockModel extends Model
{
	protected $db;
	public $session;
	public $tableStock;

	public function __construct(ConnectionInterface $db)
	{
		$this->db = $db;
		$this->tableStock = $this->db->table('stock_merchandise');
	}

	public function addStock($array = [])
	{
		return $this->tableStock->insert($array);
	}

	public function editStock($array = [], $id='') 
	{
		$this->tableStock->where('id_stock', $id)->update($array);
	}

	public function showRecord($id)
	{
		return $this->tableStock->select("
		id_stock,id_merchandise,title,decrease,stock_merchandise.created_at, pu_by, 
		amount_by, (pu_by*amount_by) as pta")
			->join('merchandise', 'merchandise.id = stock_merchandise.id_merchandise')
			->getWhere(array(
				'stock_merchandise.deleted' => 0,
				'merchandise.deleted' => 0,
				"id" => $id
			))
			->getResult();
	}

	public function showStockItem($id){  
		return $this->tableStock->select("*")->getWhere(array("deleted" => 0, "id_stock"=>$id))->getResult();
	}

	public function deleteRecord($id)
	{
		return	$this->tableStock->where('id_stock', $id)->update(array("deleted" => 1));
	}

	public function  getAllRecords()
	{
		$result = $this->tableStock->select("id_stock,id_merchandise,title,decrease,stock_merchandise.created_at, pu_by, 
		amount_by, saler, (pu_by*amount_by - decrease) as total_price")->orderBy('id_stock', 'DESC')->join('merchandise', 'merchandise.id = stock_merchandise.id_merchandise')
			->getWhere(array('stock_merchandise.deleted' => 0, 'merchandise.deleted' => 0));
		return $result->getResult();
	}
}
