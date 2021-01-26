<?php 


/**
 * 
 */
class Database
{
	private $db_host;
	private $db_login;
	private $db_password;
	private $db_name;
	private $PDO;


	public function __construct()
	{
		$this->db_host = "localhost";
		$this->db_login = "root";
		$this->db_password = "root";
		$this->db_name = "camping";

	}

	public function connectDB()
	{
		try{
			$this->PDO = new PDO("mysql:dbname=$this->db_name;host=$this->db_host;", $this->db_login, $this->db_password);
			return $this->PDO;
		}
		catch(PDOException $e){
			echo 'Connexion échouée : ' . $e->getMessage();
		}
	}
}







?>