<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

use CodeIgniter\RESTful\ResourceController;
use App\Models\stockModel;
use App\Models\merchandiseModel;

class stock_merchandise extends ResourceController
{
	protected $format = 'json';
	protected $stock_merchandise;
	protected $merchandiseModel;

	public function __construct()
	{
		$this->db = \config\Database::connect();
		$this->stock_merchandise = new stockModel($this->db);
		$this->merchandiseModel = new merchandiseModel($this->db);
	}

	public function index()
	{
		$dataAllRecords = $this->stock_merchandise->getAllRecords();
		return $this->respond($dataAllRecords);
	}

	public function create()
	{
		helper(['form']);
		$rules = [
			'amount_by' => 'required',
			'id_merchandise' => 'is_not_unique[merchandise.id]',
			'pu_by' => 'required',
		];
		if (!$this->validate($rules)) {
			return $this->fail(implode('<br>', $this->validator->getErrors()));
		} else {
			$data = array(
				'id_stock' => null,
				'id_merchandise' => $this->request->getPost('id_merchandise'),
				'description' => $this->request->getPost('description'),
				'pu_by' => $this->request->getPost('pu_by'),
				'amount_by' => $this->request->getPost('amount_by'),
				'decrease' => $this->request->getPost('decrease'),
				'saler' => $this->request->getPost('saler'),
				'created_at' => Time::createFromDate(),
				'deleted' => 0
			);
			$this->stock_merchandise->addStock($data);

			return $this->respondCreated($data);
		}
	}

	public function edit($id = null)
	{
		helper(['form']);
		$rules = [
			'id_stock' => 'is_not_unique[stock_merchandise.id_stock]',
			'id_merchandise' => 'is_not_unique[merchandise.id]',
			'amount_by' => 'required',
			'pu_by' => 'required',
		];

		if (!$this->validate($rules)) {
			return $this->fail(implode('<br>', $this->validator->getErrors()));
		} else {
			$date = $this->request->getPost('created_at');
			if ($date == null)
				$date = Time::createFromDate();
			$data = array(
				'id_merchandise' => $this->request->getPost('id_merchandise'),
				'pu_by' => $this->request->getPost('pu_by'),
				'amount_by' => $this->request->getPost('amount_by'),
				'decrease' => $this->request->getPost('decrease'),
				'saler' => $this->request->getPost('saler'),
				'created_at' => $date
			);
			$this->stock_merchandise->editStock($data, $id);
			return $this->respondCreated($data);
		}
	}

	public function show($id = null)
	{
		return $this->respond($this->stock_merchandise->showRecord($id));
	}

	public function delete($id = null)
	{
		$data = $this->stock_merchandise->showStockItem($id);
		if ($data) {
			$this->stock_merchandise->deleteRecord($id);
			return $this->respondDeleted($data);
		} else {
			return $this->failNotFound('Item not found');
		}
	}
}
