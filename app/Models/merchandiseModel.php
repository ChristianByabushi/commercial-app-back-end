<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class merchandiseModel extends Model
{
    protected $db;
    public $session;
    public $tableMerchandise;

    public function __construct(ConnectionInterface $db)
    {
        $this->db = $db;
        $this->tableMerchandise = $this->db->table('merchandise');
    }

    public function addMerchandise($array = [])
    {
        return $this->tableMerchandise->insert($array);
    }

    public function editMerchandise($array = [], $id='')
    {
        $this->tableMerchandise->where('id', $id)->update($array);
    }

    public function showRecord($id)
    {
        return $this->tableMerchandise->select("*")->getWhere(array("deleted" => 0, "id" => $id))->getResult();
    }
    public function verifyInstock($id)
    {
        return $this->db->table('stock_merchandise')->select("*")->getWhere(array("deleted" => 0, "id_merchandise" => $id))->getResult();
    }
    public function deleteRecord($id)
    {
        return    $this->tableMerchandise->where('id', $id)->update(array("deleted" => 1));
    }

    public function  getAllRecords()
    {
        $result = $this->tableMerchandise->select("*")->orderBy('id', 'DESC')->getWhere(array("deleted" => 0));
        return $result->getResult();
    }
}
