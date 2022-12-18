<?php

namespace App\Controllers;

use \App\Libraries\Oauth;
use CodeIgniter\I18n\Time;
use \OAuth2\Request;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\accountModel;

class User extends BaseController
{
	use ResponseTrait;
	protected $accountModel;

	public function __construct()
	{
		$this->db = \config\Database::connect();
		$this->accountModel = new accountModel($this->db);
	}

	public function login()
	{
		$oauth = new Oauth();
		$request = new Request();
		$respond = $oauth->server->handleTokenRequest($request->createFromGlobals());
		$code = $respond->getStatusCode();
		$body = $respond->getResponseBody();
		return $this->respond(json_decode($body), $code);
	}

	public function register()
	{
		helper('form');
		$data = [];

		if ($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		$rules = [
			'firstname' => ['rules' => 'required|min_length[3]|max_length[20]', 'label' => 'First name'],
			'lastname' => 'required|min_length[3]|max_length[20]',
			'scope' => 'required',
			'email' => [
				'rules'  => 'valid_email|is_unique[users.email]',
				'errors' => [
					'valid_email' => 'L\'adresse mail doit etre unique et valide',
				],
			],
			'password' => ['rules' => 'required|min_length[8]'],
			'password_confirm' => ['rules' => 'matches[password]', 'label' => 'Le mot de passe doivent etre les memes'],
		];

		if (!$this->validate($rules)) {
			$data = [
				'error' => implode('<br>', $this->validator->getErrors()),
				'errorstate' => true
			];
			return $this->respond($data);
		} else {
			$model = new UserModel();

			$firtname = $this->request->getVar('firstname');
			$lastname = $this->request->getVar('lastname');
			$email =  $this->request->getVar('email');
			$password = $this->request->getVar('password');
			$scope = $this->request->getVar('scope');


			$data = [
				'firstname' => $firtname,
				'lastname' => $lastname,
				'scope' => $this->request->getPost('scope'),
				'created_at' => Time::createFromDate(),
				'email' => $email,
				'password' => $password,
			];

			$user_id = $model->insert($data);
			$data['id'] = $user_id;
			unset($data['password']);

			//for the userTable
			$dataUser = [
				'username' => $data['id'],
				'first_name' => $firtname,
				'last_name' => $lastname,
				'password' => $password,
				'email' => $email,
				'email_verified' => 1,
				'scope' => $scope
			];

			//insert in user table
			$this->accountModel->addUser($dataUser);

			//insert into client table  
			//for the client table
			$dataClient = [
				'client_id' => $email,
				'client_secret' => $password,
				'redirect_uri' => '',
				'grant_types' => 'password',
				'scope' => $scope,
				'user_id' => $data['id']
			];
			$this->accountModel->addClient($dataClient);

			return $this->respond($dataUser);
		}
	}

	public function getInforAccount($email)
	{
		$data =	$this->accountModel->getInforAccount($email);
		return	$this->respond($data);
	}

	public function verifyUserPassword()
	{
		if (!$this->accountModel->verifyUserPassword("", ""))
			return $this->fail("Le mot de passe fourni est incorrect");
	}

	public function editPwdAccount()
	{
		$model = new UserModel();
		helper('form');
		$data = [];

		if ($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		$rules = [
			'email' => 'required|valid_email|is_not_unique[users.email]',
			'idUser' => 'is_not_unique[users.id]',
			'password' => 'required|min_length[8]',
			'newpassword' => 'required|min_length[8]',
		];

		if (!$this->validate($rules)) {
			return $this->fail(implode('<br>', $this->validator->getErrors()));
		} else {

			$password = $this->request->getVar('password');
			$newpassword = $this->request->getVar('newpassword');
			$email =  $this->request->getVar('email');

			//verify if the  passsowrd is correct
			if (!$this->accountModel->verifyUserPassword($password, $email))
				return $this->fail("Le mot de passe fourni est incorrect");

			//in the table users
			$idUser = $this->request->getVar('idUser');
			$data = [
				'password' => $newpassword,
				'id' => $idUser,
			];

			$model->update($idUser, $data);

			//for edit userTable
			$dataUser = [
				'username' => $data['id'],
				'password' => $newpassword,
			];

			//insert into user table
			$this->accountModel->updateUser($dataUser, $idUser);

			//for edit clientTable
			//insert into client table  
			//for the client table
			$dataClient = [
				'client_secret' => $newpassword,
				'user_id' => $data['id']
			];
			$this->accountModel->updateClient($dataClient, $email);
		}
		return	$this->respondCreated(array("Mot de passe changé avec succès réussie avec succès"));
	}

	public function getInfoByid($id)
	{
		$data =	$this->accountModel->getInfoByid($id);
		return	$this->respond($data);
	}

	public function delete($id)
	{
		$model = new UserModel();
		$model->delete($id);
		return $this->delete($id);
	}

	public function unandlock($id)
	{
		$this->accountModel->unandlockAccount($id);
		return $this->respond(['success' => 'Success']);
	}

	public function editAccount()
	{
		$model = new UserModel();
		helper('form');
		$data = [];

		if ($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		$rules = [
			'firstname' => ['rules' => 'required|min_length[3]|max_length[20]', 'label' => 'First name'],
			'lastname' => 'required|min_length[3]|max_length[20]',
			'email' => 'required|valid_email|is_not_unique[users.email]',
			'new_email' => 'required|valid_email|',
			'idUser' => 'is_not_unique[users.id]',
			'password' => 'required|min_length[8]',
		];

		if (!$this->validate($rules)) {
			return $this->fail(implode('<br>', $this->validator->getErrors()));
		} else {
			$password = $this->request->getVar('password');
			$email =  $this->request->getVar('email');
			$tmpemail =  $this->request->getVar('email');
			$new_email = $this->request->getVar('new_email');
			$scope = $this->request->getVar('scope');

			//verify if the  passsowrd is correct, jump this part because it's the admin
			if (!$this->accountModel->verifyUserPassword($password, $email))
				return $this->fail("Le mot de passe fourni est incorrect");

			//if new_email != email, i verify if new_email exist in the db
			if ($new_email != $email) {
				if ($this->accountModel->verifyNewEmail($new_email))
					return $this->fail("Le nouveau existe dejà dans la base des données fourni est incorrect");
				else
					//the new email becomes the right email
					$email = $new_email;
			}

			//in the table users
			$firtname = $this->request->getVar('firstname');
			$lastname = $this->request->getVar('lastname');
			$idUser = $this->accountModel->getInforAccount($email)->id;

			$data = [
				'firstname' => $firtname,
				'lastname' => $lastname,
				'email' => $email,
				'password' => $password,
				'id' => $idUser,
			];

			$model->update($idUser, $data);

			//for edit userTable
			$dataUser = [
				'username' => $data['id'],
				'first_name' => $firtname,
				'last_name' => $lastname,
				'password' => $password,
				'email' => $email,
				'email_verified' => 1,
			];

			//insert into user table
			$this->accountModel->updateUser($dataUser, $idUser);

			//for edit clientTable
			//insert into client table  
			//for the client table
			$dataClient = [
				'client_id' => $email,
				'client_secret' => $password,
				'redirect_uri' => '',
				'grant_types' => 'password',
				'scope' => $scope,
				'user_id' => $data['id']
			];
			$this->accountModel->updateClient($dataClient, $tmpemail);
		}
		return	$this->respondCreated(array("Opération réussie avec succès"));
	}

	public function editAccountUser()
	{
		$model = new UserModel();
		helper('form');
		$data = [];

		if ($this->request->getMethod() != 'post')
			return $this->fail('Only post request is allowed');

		$rules = [
			'firstname' => ['rules' => 'required|min_length[3]|max_length[20]', 'label' => 'First name'],
			'lastname' => 'required|min_length[3]|max_length[20]',
			'email' => 'required|valid_email|is_not_unique[users.email]',
			'new_email' => 'required|valid_email|',
			'id' => 'is_not_unique[users.id]',
			'password' => 'required|min_length[8]',
		];

		if (!$this->validate($rules)) {
			$data = [
				'error' => implode('<br>', $this->validator->getErrors()),
				'errorstate' => true
			];
			return $this->respond($data);
		} else {
			$password = $this->request->getVar('password');
			$email =  $this->request->getVar('email');
			$tmpemail =  $this->request->getVar('email');
			$new_email = $this->request->getVar('new_email');
			$scope = $this->request->getVar('scope');

			//verify if the  passsowrd is correct
			// if (!$this->accountModel->verifyUserPassword($password, $email))
			// 	return $this->fail("Le mot de passe fourni est incorrect");

			//if new_email != email, i verify if new_email exist in the db
			if ($new_email != $email) {
				if ($this->accountModel->verifyNewEmail($new_email))
					return $this->fail("Le nouveau existe dejà dans la base des données fourni est incorrect");
				else
					//the new email becomes the right email
					$email = $new_email;
			}

			//in the table users
			$firtname = $this->request->getVar('firstname');
			$lastname = $this->request->getVar('lastname');
			$idUser = $this->request->getVar('id');

			$data = [
				'firstname' => $firtname,
				'lastname' => $lastname,
				'email' => $email,
				'password' => $password,
				'scope' => $scope,
				'id' => $idUser,
			];

			$model->update($idUser, $data);

			//for edit userTable
			$dataUser = [
				'username' => $data['id'],
				'first_name' => $firtname,
				'last_name' => $lastname,
				'password' => $password,
				'email' => $email,
				'email_verified' => 1,
			];

			//insert into user table
			$this->accountModel->updateUser($dataUser, $idUser);

			//for edit clientTable
			//insert into client table  
			//for the client table
			$dataClient = [
				'client_id' => $email,
				'client_secret' => $password,
				'redirect_uri' => '',
				'grant_types' => 'password',
				'scope' => $scope,
				'user_id' => $data['id']
			];

			$this->accountModel->updateClient($dataClient, $tmpemail);
		}
		return	$this->respondCreated(array("Opération réussie avec succès"));
	}

	public function addDeclarant($data)
	{
		$this->accountModel->addDeclarant($data);
	}

	public function getAllusers($type = "")
	{
		$data = $this->accountModel->getAllUsers($type);
		return	$this->respond($data);
	}

	public function getDeclarant($data = '')
	{
		return $this->respond($this->accountModel->getDeclarant($data));
	}
	public function deletedDeclarant($id)
	{
		$this->accountModel->deletedDeclarant($id);
		return $this->respond('Suppression effectuée avec succès');
	}

	public function oneDeclarant($id)
	{
		return $this->respond($this->accountModel->oneDeclarant($id));
	}
}
