<?php

	namespace App\Model;
	
	use App\Model\Conexao;

	class Funcionario extends Conexao{
		
		public function __construct(){
		
			parent::__construct();
			
		}
		
		//busca os registros dos funcionários no banco de dados de acordo com os filtros
		public function index($filtros = null){
		
			try{
				
				//utiliza a view de funcionários
				$query = "SELECT * FROM vw_funcionarios";
				$where = array();
				
				//filtragem pelo nome
				if(isset($filtros['nome']) && $filtros['nome'] != "" && $filtros['nome'] != null){
				
					$where[] = array(
						"filtro" => "funcionario = :funcionario",
						"tipo" => "str",
						"campo" => ":funcionario",
						"valor" => $filtros['nome']
					);
					
				}
				
				$j = count($where);
				
				//montando a query com os filtros
				for($i = 0; $i < $j; $i++){
				
					if($i == 0){
						$query .= " WHERE {$where[$i]['filtro']}";
					}else{
						$query .= " AND {$where[$i]['filtro']}";	
					}
					
				}
				
				//limite de registros
				if(isset($filtros['limit'])){
				
					$query .= " LIMIT {$filtros['limit']}";
					
				}
				
				$sql = $this->pdo->prepare($query);
				
				//colocando os parâmetros de filtragem
				foreach($where as $item){
				
					switch($item['tipo']){
					
						case 'str':
						
							$sql->bindParam($item['campo'], $item['valor'], \PDO::PARAM_STR);
						
						break;
						
						case 'int':
						
							$sql->bindParam($item['campo'], $item['valor'], \PDO::PARAM_INT);
						
						break;
						
						default:
						
							$sql->bindParam($item['campo'], $item['valor'], \PDO::PARAM_STR);
						
						break;
						
					}
					
				}
				
				$sql->execute();
				
				//colocando os registros encontrados no retorno
				if($sql->rowCount() > 0){
					
					$registros = array();
					
					while($row = $sql->fetch()){
					
						$registros[] = array(
							"id" => $row['id'],
							"funcionario" => $row['funcionario'],
							"empresa" => $row['empresa']
						);
						
					}
				
					$retorno = array(
						"success" => true,
						"msg" => "Funcionário encontrado com sucesso!",
						"dados" => $registros
					);
					
				}else{
					
					$retorno = array(
						"success" => false,
						"msg" => "Nenhum funcionário encontrado!"
					);
					
				}
				
			}catch(Exception $e){
			
				$retorno = array(
					"success" => false,
					"msg" => "Não foi possível buscar os dados dos clientes!",
					"erro" => $e->getMessage()
				);
				
			}
			
			return $retorno;
		}
		
	}

?>
