<?php

namespace App\Controllers;

use \App\Libraries\Oauth;
use \OAuth2\Request;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\I18n\Time;
use App\Models\accountModel;
use App\Models\messagesModel;

class messages extends BaseController
{
	use ResponseTrait;
	protected $acteModel;

	public function __construct()
	{
		$this->db = \config\Database::connect();
		$this->messagesModel = new messagesModel($this->db);
	}
	public function getMessages($userId)
	{
		$data = $this->messagesModel->getMessages($userId);
		return $this->respond($data);
	} 
	
	public function usersMsg($email = '')
	{
		$data = $this->messagesModel->usersMsg($email);
		return $this->respond($data);
	}
	public function create()
	{
		helper('form');
		$rules = [
			'writer' =>  'is_not_unique[users.id]',
			'email' =>  'is_not_unique[users.email]',
		];
		if (!$this->validate($rules)) {
			$data = [
				'error' => implode('<br>', $this->validator->getErrors()),
				'errorstate' => true
			];
			return $this->respond($data);
		} else {
			//get the idOf Receiver of the msg by his email
			$idReceiver = $this->messagesModel->usersMsg($this->request->getVar('email'))->id;
			$data = [
				'idService' => null,
				'message' => $this->request->getVar('message'),
				'writer' => $this->request->getVar('writer'),
				'receiver' => $idReceiver,
				'kind' => 0,
				'created_at' => $this->request->getVar('created_at'),
			];
			$data = $this->messagesModel->createMsg($data);
			return $this->respond("Msg envoyé avec succès");
		}
	}
}
