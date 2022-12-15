<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use App\Models\invoiceModel;
use App\Models\stockModel;

class result extends ResourceController
{
	protected $format = 'json';
	protected $invoice;
	protected $stockModel;
	
	public function __construct()
	{
		$this->db = \config\Database::connect();
		$this->invoice = new invoiceModel($this->db);
		$this->stockModel = new stockModel($this->db);
	}

	public function index()
	{
		$dataAllRecords = $this->invoice->getAllRecords();
		return $this->respond($dataAllRecords);
	}

	public function verifyAmount()
	{
		helper(['form']);
		$rules = [
			'id_stock' => 'required',
			'amount_sale' => 'required',
			'id_stock' => 'is_not_unique[stock_merchandise.id_stock]',
			'pu_sale' => 'required',
		];
		$amount_remaining = $this->invoice->verifyAmount($this->request->getPost('id_stock'));

		if ((float) $amount_remaining >= $this->request->getPost('amount_sale')) {
			return $this->fail("Qté démandé non présente en stock");
		} else {
			return $this->respondCreated((float) $amount_remaining);
		}
	}
	public function create()
	{
		helper(['form']);
		$rules = [
			'amount_sale' => 'required',
			'id_stock' => 'is_not_unique[stock_merchandise.id_stock]',
			'pu_sale' => 'required',
		];

		if (!$this->validate($rules))
			return $this->fail(implode('<br>', $this->validator->getErrors()));

		$amount_remaining = $this->invoice->verifyAmount($this->request->getPost('id_stock'));
		$data = array(
			'id_invoice' => null,
			'id_stock' => $this->request->getPost('id_stock'),
			'client' => $this->request->getPost('client'),
			'decrease' => $this->request->getPost('decrease'),
			'pu_sale' => $this->request->getPost('pu_sale'),
			'amount_sale' => $this->request->getPost('amount_sale'),
			'created_at' => Time::createFromDate(),
			'deleted' => 0
		);

		if ((float) $amount_remaining >= $this->request->getPost('amount_sale')) {
			$this->invoice->addinvoice($data);
			return $this->respondCreated($data);
		} else {
			return $this->fail("Qté démandée non présente en stock");
		}
	}

	public function edit($id = null)
	{

		helper(['form']);
		$rules = [
			'amount_sale' => 'required',
			'id_invoice' => 'is_not_unique[invoice.id_invoice]',
			'id_stock' => 'is_not_unique[stock_merchandise.id_stock]',
			'pu_sale' => 'required',
		];

		if (!$this->validate($rules))
			return $this->fail(implode('<br>', $this->validator->getErrors()));

		$amount_remaining = $this->invoice->verifyAmount($this->request->getPost('id_stock'));
		$date = $this->request->getPost('created_at');
		
		if ($date == null)
			$date = Time::createFromDate();

		$data = array(
			'id_invoice' => null,
			'id_stock' => $this->request->getPost('id_stock'),
			'client' => $this->request->getPost('client'),
			'decrease' => $this->request->getPost('decrease'),
			'pu_sale' => $this->request->getPost('pu_sale'),
			'amount_sale' => $this->request->getPost('amount_sale'),
			'created_at' => Time::createFromDate(),
			'deleted' => 0
		);

		if ((float) $amount_remaining >= $this->request->getPost('amount_sale')) {
			$this->invoice->addinvoice($data);
			return $this->respondCreated($data);
		} else {
			return $this->fail("Qté à vouloir modifié non présente en stock");
		}
	}

	public function show($id = null)
	{
		return $this->respond($this->invoice->showRecord($id));
	}

	public function delete($id = null)
	{
		if ($this->invoice->showRecord($id)) {
			$data = $this->invoice->deleteRecord($id);
			return $this->respondDeleted($data);
		} else {
			return $this->fail("Référence non présente dans la liste des ventes!");
		}
	}
}
