<?php

	class Pedido extends Conexao{
	
		public function __construct(){
			
			parent::__construct();
			
		}
		
		//busca todas as quantidades limites de cada tipo de prato no banco de dados
		public function getControle(){
		
			try{
				
				$sql = $this->pdo->prepare("SELECT * FROM controle_quantidade");
				$sql->execute();
				
				if($sql->rowCount() > 0){
					
					$registros = array();
					
					while($row = $sql->fetch()){
						
						$registros[] = array(
							"tipo" => $row['tipo_prato'],
							"quantidade" => intval($row['quantidade'])
						);
							
					}
					
					$retorno = array(
						"success" => true,
						"msg" => "Controle de quantidade carregado com sucesso!",
						"dados" => $registros
					);
					
				}else{
				
					$retorno = array(
						"success" => false,
						"msg" => "Nenhum controle de quantidade encontrado!"
					);
					
				}
				
			}catch(Exception $e){
			
				$retorno = array(
					"success" => false,
					"msg" => "Não foi possível carregar o controle de quantidade dos pedidos!",
					"erro" => $e->getMessage()
				);
				
			}
			
			return $retorno;
		}
		
		//insere um novo pedido no banco de dados
		public function insert($pedido){
		
			try{
				
				//pegando os dados do pedido
				$funcionario = $pedido['funcionario'];
				$obs = $pedido['obs'];
				$pagamento = $pedido['pagamento'];
				$pratos = $pedido['pratos'];
				
				//verifica se o nome do funcionário está no banco de dados
				$sql = $this->pdo->prepare("SELECT * FROM funcionarios WHERE nome = :nome");
				$sql->bindParam(":nome", $funcionario, PDO::PARAM_STR);
				$sql->execute();
				
				if($sql->rowCount() > 0){
					
					//pegando o id do funcionário encontrado para utilizá-lo como chave estrangeira para o registro do pedido
					$row = $sql->fetch();
					$fk_funcionario = $row['id'];
					
					$sql = $this->pdo->prepare("INSERT INTO funcionarios_pedidos(fk_funcionario, obs, pagamento, dt_registro)VALUES(:fk_funcionario, :obs, :pagamento, NOW())");
					$sql->bindParam(":fk_funcionario", $fk_funcionario, PDO::PARAM_INT);
					$sql->bindParam(":obs", $obs, PDO::PARAM_STR);
					$sql->bindParam(":pagamento", $pagamento, PDO::PARAM_STR);
					$sql->execute();
					
					if($sql->rowCount() > 0){
						
						//pegando o id gerado do registro do pedido para utilizá-lo como chave estrangeira para registrar os pratos do pedido
						$sql = $this->pdo->prepare("SELECT LAST_INSERT_ID()");
						$sql->execute();
						
						$row = $sql->fetch();
						$fk_pedido = $row[0];
						
						$erros = array();
						
						//inserindo os pratos do pedido no banco de dados
						foreach($pratos as $prato){
						
							$preco = $this->convertPreco($prato['preco']);
						
							$sql = $this->pdo->prepare("INSERT INTO pedidos(descricao_prato, tipo_prato, quantidade, valor, fk_funcionario_pedido)VALUES(:descricao, :tipo, :quantidade, :valor, :fk_pedido)");
							$sql->bindParam(":descricao", $prato['descricao'], PDO::PARAM_STR);
							$sql->bindParam(":tipo", $prato['tipo'], PDO::PARAM_STR);
							$sql->bindParam(":quantidade", $prato['quantidade'], PDO::PARAM_INT);
							$sql->bindParam(":valor", $preco, PDO::PARAM_STR);
							$sql->bindParam(":fk_pedido", $fk_pedido, PDO::PARAM_INT);
							$sql->execute();
							
							if($sql->rowCount() == 0){
							
								$erros[] = "Não inserido: {$prato['descricao']}";
								
							}

						}
						
						if($erros){
						
							$retorno = array(
								"success" => false,
								"msg" => "Nem todos os pratos foram inseridos!",
								"erros" => $erros
							);
							
						}else{
						
							$retorno = array(
								"success" => true,
								"id" => $fk_pedido,
								"msg" => "Pedido Cadastrado com sucesso!"
							);
							
						}
						
					}else{
					
						$retorno = array(
							"success" => false,
							"msg" => "Pedido não inserido!"
						);
						
					}
	
				}else{
				
					$retorno = array(
						"success" => false,
						"msg" => "Coloque o nome de um funcionário válido!"
					);
					
				}
				
				
			}catch(Exception $e){
			
				$retorno = array(
					"success" => false,
					"msg" => "Erro ao registrar pedido!",
					"erro" => $e->getMessage()
				);
				
			}
			
			return $retorno;
		}
		
		//busca os dados de determinado pedido pelo id no banco de dados
		public function find($id){
		
			try{
				
				$sql = $this->pdo->prepare("SELECT * FROM vw_funcionarios_pedidos WHERE id = :id");
				$sql->bindParam(":id", $id, PDO::PARAM_INT);
				$sql->execute();
				
				if($sql->rowCount() > 0){
					
					$row = $sql->fetch();
					
					$funcionario = $row['funcionario'];
					$empresa = $row['empresa'];
					$obs = $row['obs'];
					$pagamento = $row['pagamento'];
					$total = number_format(floatval($row['total']), 2, ',', '.');
					$dataRegistro = $row['data_registro'];
					$horaRegistro = $row['hora_registro'];
					
					$sql = $this->pdo->prepare("SELECT * FROM vw_pedidos WHERE fk_funcionario_pedido = :id");
					$sql->bindParam(":id", $id, PDO::PARAM_INT);
					$sql->execute();
					
					if($sql->rowCount() > 0){
					
						$pratos = array();
						
						while($row = $sql->fetch()){
						
							$pratos[] = array(
								"descricao" => $row['prato'],
								"tipo" => $row['tipo'],
								"valor" => number_format(floatval($row['valor']), 2, ',', '.'),
								"quantidade" => $row['quantidade'],
								"total" => number_format(floatval($row['total']), 2, ',', '.')
							);
							
						}
						
						$retorno = array(
							"success" => true,
							"msg" => "Pedido cadastrado com sucesso!",
							"funcionario" => $funcionario,
							"empresa" => $empresa,
							"data" => $dataRegistro,
							"hora" => $horaRegistro,
							"obs" => $obs,
							"pagamento" => $pagamento,
							"total" => $total,
							"pratos" => $pratos
						);
						
					}else{
						
						$retorno = array(
							"success" => false,
							"msg" => "Nenhum prato registrado nesse pedido!",
							"funcionario" => $funcionario,
							"obs" => $obs,
							"pagamento" => $pagamento,
							"total" => $total,
							"pratos" => null
						);
						
					}
					
				}else{
				
					$retorno = array(
						"success" => false,
						"msg" => "Nenhum pedido encontrado!"
					);
					
				}
				
			}catch(Exception $e){
				
				$retorno = array(
					"success" => false,
					"msg" => "Erro ao buscar dados do pedido!",
					"erro" => $e->getMessage()
				);
				
			}
			
			return $retorno;
		}
		
		//converte o preço em um valor float
		public function convertPreco($preco){
			
			$preco = explode(',', $preco);
			$preco = "{$preco[0]}.{$preco[1]}";
			
			return floatval($preco);
			
		}
		
		
	}

?>
