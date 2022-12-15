<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use App\Models\line_InvoiceModel;
use App\Models\invoiceModel;
use App\Models\stockModel;

class invoice extends ResourceController
{
	protected $format = 'json';
	protected $invoice;
	protected $stockModel;

	public function __construct()
	{
		$this->db = \config\Database::connect();
		$this->invoice = new invoiceModel($this->db);
		$this->line_invoice = new line_InvoiceModel($this->db);
		$this->stockModel = new stockModel($this->db);
	}

	public function index()
	{
		$dataAllRecords = $this->invoice->getAllRecords();
		return $this->respond($dataAllRecords);
	}


	public function verifyAmount($idStock = null)
	{
		$amount_remaining = $this->invoice->verifyAmount($idStock);
		return	$this->respond($amount_remaining);
	}

	public function create()
	{
		$data = $this->request->getPost('listOfitemInvoices');
		$getLastIdLineInvoice = $this->line_invoice->getLastId();
		$data = json_decode($data);

		foreach ($data as $value) {
			$invoice = array(
				'id_invoice' => null,
				'id_stock' => (int) $value->id_stock,
				'id_line' => $getLastIdLineInvoice->id_line,
				'pu_sale' => (float) abs($value->pu_sale),
				'amount_sale' => (float) abs($value->amount_sale),
				'deleted' => 0
			);
			$this->invoice->addinvoice($invoice);
		}
		return $this->respondCreated($data);
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
			'pu_sale' =>  (float) abs($this->request->getPost('pu_sale')),
			'amount_sale' => (float) abs($this->request->getPost('amount_sale')),
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
