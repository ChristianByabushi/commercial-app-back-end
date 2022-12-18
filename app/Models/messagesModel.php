<?php

namespace App\Models;

use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Model;

class messagesModel extends Model
{
	protected $db;
	public $session;
	public $tableinvoice;

	public function __construct(ConnectionInterface $db)
	{
		$this->db = $db;
		$this->tablemessage = $this->db->table('servicemessage');
	}
	public function getMessages($Userid = '')
	{
		$query = "SELECT 
		 servicemessage.idService, 
		 servicemessage.message, 
		 servicemessage.created_at, 
		 servicemessage.kind,
		 firstnameReceiver,
		 emailReceiver,
		 firstnameWriter, 
		 servicemessage.writer,
		 servicemessage.receiver
    from servicemessage 
		LEFT JOIN (
		 SELECT receiver, 
		 firstname AS firstnameReceiver, email AS emailReceiver
		 FROM users, servicemessage WHERE servicemessage.receiver = users.id GROUP BY 1  
		 ) 
		 AS users ON users.receiver = servicemessage.receiver
	 
		 LEFT JOIN (
			 SELECT writer, 
			 firstname AS firstnameWriter, email AS emailWriter
			 FROM users,servicemessage WHERE servicemessage.writer = users.id GROUP BY 1 
		 ) 
		 AS userWriter ON userWriter.writer = servicemessage.writer 
	 WHERE servicemessage.writer = $Userid OR servicemessage.receiver = $Userid";
		return $this->db->query($query)->getResult();
	}
	public function usersMsg($email)
	{
		$query = "SELECT id, email From users where email = '$email'";
		return $this->db->query($query)->getRow();
	}
	public function createMsg($data = [])
	{
		$this->tablemessage->insert($data);
		return true;
	}
}
