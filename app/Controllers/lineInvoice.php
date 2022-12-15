<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;
use CodeIgniter\RESTful\ResourceController;
use App\Models\invoiceModel;
use App\Models\line_InvoiceModel;
use App\Models\resultModel;

class lineInvoice extends ResourceController
{
	protected $format = 'json';
	protected $line_invoice;
	protected $stockModel;
	protected $result;

	public function __construct()
	{
		$this->db = \config\Database::connect();
		$this->invoice = new invoiceModel($this->db);
		$this->line_invoice = new line_InvoiceModel($this->db);
		$this->result = new resultModel($this->db);
	}

	public function index()
	{
		$data = $this->line_invoice->getAllInvoices();
		return $this->respondCreated($data);
	}

	public function create()
	{
		helper(['form']);
		$rules = [
			'client' => 'required',
		];
		if (!$this->validate($rules))
			return $this->fail(implode('<br>', $this->validator->getErrors()));
		$data = array(
			'id_line' => null,
			'client' => $this->request->getPost('client'),
			'decrease' => $this->request->getPost('decrease'),
			'created_at' => $this->request->getPost('created_at'),
			'deleted' => 0
		);
		if ($this->line_invoice->addlineinvoice($data)) {
			return $this->respondCreated($data);
		} else {
			return $this->fail("Echec d'enregistrement");
		};
	}

	public function deleteInvoice($id = null)
	{
		$data = $this->line_invoice->deleteInvoice($id);
		return $this->respondDeleted($data);
	}

	public function getInvoiceInfo($lineid = null)
	{
		$data = $this->line_invoice->getInvoiceInfo($lineid);
		return $this->respondCreated($data);
	}

	public function getInlineInfo($lineid = null)
	{
		$data = $this->line_invoice->getInlineInfo($lineid);
		return $this->respondCreated($data);
	}

	// we gonna considerate this function for each line not lines, related to each item!
	public function getResultInvoices()
	{
		$date = $this->request->getPost('created_at');
		$itemSelected = $this->request->getPost('itemSelected');
		$data = $this->result->getResultInvoices($date, $itemSelected);
		return $this->respondCreated($data);
	}

	// generally want to find the result found from each lineInvoice related to the purchase of the each client
	public function getResultLinesInvoice($date = null)
	{
		$data =  $this->result->getResultLinesInvoice($date);
		return $this->respondCreated($data);
	}


	//return totalAmountby after a certain period
	public function dashboardanalysis($date = null)
	{
		$data =  array(
			'totalAmountby' => $this->result->totalAmountby($date),
			'totalAmountSold' => $this->result->totalAmountSold($date),
			'highSoldAchieved' => $this->result->highSoldAchieved($date),
			'getResultInvoices' => $this->result->getResultInvoices($date),
			'DetailsItems' => $this->result->DetailsItems()
		);

		return $this->respondCreated($data);
	}
}
