<?php
	
	class DBConnection{
		private $host;
		private $user;
		private $pass;
		private $connection;
		private $database;
	
		public function DBConnection($host, $user, $pass, $database){
			$this->host = $host;
			$this->user = $user;
			$this->pass = $pass;
			$this->database = $database;
		}
		
		public function openDBConnection(){
			//apre la connessione al datbase e restituisce il percorso
			
                            $this->connection = mysql_connect($this->host, $this->user, $this->pass) or die(mysql_error($db));
                            mysql_select_db($this->database, $this->connection) or die(mysql_error($db));
                        
			return $this->getConnection();
		}
		
		public function closeDBConnection(){
			//chiude la connessione e restituisce esito positivo o negativo
			if(mysql_close($this->connection) or die(mysql_error($this->connection)))
				return true;
			else
				return false;
		}
		
		public function freeSwap($result){
			if(mysql_free_result($result) or die(mysql_error($this->connection)))
				return true;
			else
				return false;
		}
		
		public function executeQuery($query){
				set_time_limit(600);
				//$query = mysqli_prepare($query);
				$result = mysql_query($query, $this->getConnection()) or die("Query non valida: ".mysql_error($this->connection));
				return $result;
		}
		
		public function getConnection(){
			return $this->connection;	
		}
		
		public function getDatabase(){
			return $this->database;	
		}
		
	}
?>