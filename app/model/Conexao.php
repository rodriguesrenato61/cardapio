<?php
	
	//conexão com o banco de dados
	abstract class Conexao{
	
		protected $pdo;
	
		public function __construct(){
			
			try{
				
				$host = "localhost";
				$dbname = "cardapio";
				$user = "root";
				$password = "";
				$charset = "utf8";
				
				$this->pdo = new PDO("mysql:host={$host};dbname={$dbname};charset={$charset}", $user, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
				
			}catch(PDOException $e){
			
				echo("Erro: {$e->getMessage()}");
				
			}
			
		}
		
	}

?>
