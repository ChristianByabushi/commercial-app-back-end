<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class accountModel extends Model
{
	protected $db;
	public $tableaccount;

	public function __construct(ConnectionInterface $db)
	{
		$this->db = $db;
		$this->table_account = $this->db->table('oauth_users');
	}

	public function addUser($data)
	{
		return $this->table_account->insert($data);
	}
	public function getInfoByid($id = null)
	{
		$query = "SELECT users.id, users.firstname as firstname, users.lastname as
		 lastname, users.id as id, users.email as email, users.scope from users
		where users.id = '$id'";
		return $this->db->query($query)->getRow();
	}

	public function addClient($data)
	{
		return $this->db->table('oauth_clients')->insert($data);
	}
	public function getAllUsers($type)
	{
		$condscope = '';
		// if ($type != '') {
		// 	$type = " scope = '$type'";
		// }
		if ($type == '')
			$condscope = ' ';
		else
			$condscope = " scope = '$type' and ";

		$query = "SELECT id, firstname, email, lastname, 
		scope, created_at FROM users 
		where " . $condscope . "   scope !='admin' order by created_at desc";
		return $this->db->query($query)->getResult();
	}
	public function getInforAccount($email = null)
	{
		$query = "SELECT oauth_users.first_name as firstname, oauth_users.scope as scope, oauth_users.last_name as lastname, oauth_users.username as id, oauth_users.email as email from oauth_users
		where oauth_users.email = '$email'";
		return $this->db->query($query)->getRow();
	}
	
	//username is my id according to the structure the token algo used
	public function updateUser($array = [], $id = '')
	{
		$this->table_account->where('username', $id)->update($array);
	}

	public function verifyUserPassword($password, $email)
	{
		$query = "SELECT * FROM `oauth_clients` WHERE `client_id` = '$email' AND `client_secret` = '$password' ";
		return $this->db->query($query)->getRow();
	}

	public function verifyNewEmail($new_email = '')
	{
		$query = "SELECT * FROM `oauth_clients` WHERE `client_id` = '$new_email'";
		return $this->db->query($query)->getRow();
	}

	public function updateClient($data = [], $email = '')
	{
		$this->db->table('oauth_clients')->where('client_id', $email)->update($data);
	}
}
