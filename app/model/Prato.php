<?php

	namespace App\Model;
	
	use App\Model\Conexao;

	class Prato extends Conexao{
	
		public function __construct(){
		
			parent::__construct();
			
		}
		
		//busca todos os pratos que estão armazenados no banco de dados
		public function index(){
		
			try{
				
				$sql = $this->pdo->prepare("SELECT * FROM pratos ORDER BY descricao");
				$sql->execute();
				
				//colocando os registros encontrados no retorno
				if($sql->rowCount() > 0){
					
					$registros = array();
					
					while($row = $sql->fetch()){
					
						$registros[] = array(
							"id" => $row['id'],
							"descricao" => $row['descricao'],
							"preco" => number_format((float) $row['preco'], 2, ',', '.'),
							"tipo" => $row['tipo']
						);
						
					}
					
					$retorno = array(
						"success" => true,
						"msg" => "Pratos carregados com sucesso!",
						"dados" => $registros
					);
					
				}else{
				
					$retorno = array(
						"success" => false,
						"msg" => "Nenhum prato encontrado!"
					);
					
				}
				
			}catch(Exception $e){
			
				$retorno = array(
					"success" => false,
					"msg" => utf8_encode("Não foi possível buscar os pratos!"),
					"erro" => $e->getMessage()
				);
				
			}
			
			return $retorno;
		}
		
	}

?>
