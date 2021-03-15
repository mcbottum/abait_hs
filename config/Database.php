<?php
	class Database {
		//DB Params
		private $host = 'localhost';
		private $db_name = 'agitation';
		private $username = 'abait';
		private $password = 'abait123!';
		private $conn;

		// DB Connect
		public function connect() {
			$this->conn = null;

			try {
				$this->conn = new PDO('mysql:host=' . $this->host . ';dbname' . $this->db_name,
					this->username, $this->password);
				$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
				$this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			} catch(Exception $e) {
				echo 'Connection Error: ' . $e->getMessage();
			}
			return $this->conn;
		}

		//Sample query
		// $privilege = 'globaladmin';
		// $sql = 'SELECT * FROM residentdata WHERE privilege = ?'; // positional
		// $stmt = $conn->prepare($sql);
		// $stmt->execute([$privilege]);


		// $sql = 'SELECT * FROM residentdata WHERE privilege = :privilege'; //non positional
		// $stmt = $conn->prepare($sql);
		// $stmt->execute(['privilege'=>$privilege]);
		// $data = $stmt->fetchAll(); //array of falues
		// $data = $stmt->fetch(); //single value
		// $data = $stmt->rowCount(); //row count

		// $sql = 'INSERT INTO residentdata(first, last) VALUES(:first, :last)';
		// $stmt = $conn->prepare($sql);
		// $stmt->execute(['first' => $first, 'last' => $last]);

		// TO INSTANTIATE IN ANY FILE
		// include_once 'config/Database.php'
		// $database = new Database();
		// $db = $database->connect();
		

	}