<?php

namespace App\Controllers;

use CodeIgniter\I18n\Time;

use CodeIgniter\RESTful\ResourceController;
use App\Models\merchandiseModel;
use DateTime;

class merchandise extends ResourceController
{
	protected $format = 'json';
	protected $merchandiseModel;

	public function __construct()
	{
		$this->db = \config\Database::connect();
		$this->merchandiseModel = new merchandiseModel($this->db);
	}

	public function index()
	{
		$dataAllRecords = $this->merchandiseModel->getAllRecords();
		return $this->respond($dataAllRecords);
	}

	public function create()
	{
		helper(['form']);
		$rules = [
			'title' => 'required|min_length[2]',
			'description' => 'required',
		];
		if (!$this->validate($rules)) {
			return $this->fail(implode('<br>', $this->validator->getErrors()));
		} else {
			$data = array(
				'id' => null,
				'title' => $this->request->getPost('title'),
				'description' => $this->request->getPost('description'),
				'categorie' => $this->request->getPost('categorie'),
				'created_at' => Time::createFromDate(),
				'deleted' => 0
			);
			$this->merchandiseModel->addMerchandise($data);

			return $this->respondCreated($data);
		}
	}
	public function edit($id = null)
	{
		helper(['form']);
		$rules = [
			'title' => 'required|min_length[2]',
			'description' => 'required',
		];
		if (!$this->validate($rules)) {
			return $this->fail(implode('<br>', $this->validator->getErrors()));
		} else {
			$date = $this->request->getPost('created_at');
			if ($date == null) 
				$dt = new DateTime(('now'));
				$date = Time::createFromDate(); 
			
			$data = array(
				'title' => $this->request->getPost('title'),
				'description' => $this->request->getPost('description'),
				'categorie' => $this->request->getPost('categorie'),
				'created_at' => $date,
				'deleted' => 0
			);
			$this->merchandiseModel->editMerchandise($data, $id);

			return $this->respondCreated($data);
		}
	}

	public function show($id = null)
	{
		return $this->respond($this->merchandiseModel->showRecord($id));
	}

	public function delete($id = null)
	{
		$data = $this->merchandiseModel->verifyInstock($id);
		if ($data) {
			return $this->failNotFound('Vous ne pouvez pas supprimer un bien se trouvant dejà en stock !');
		} else {
			$this->merchandiseModel->deleteRecord($id);
			return $this->respondDeleted($data);
		}
	}
}
