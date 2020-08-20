<?php


	class FuncionarioController{
		
		private $funcionario;
		
		public function __construct(){
		
			$this->funcionario = new Funcionario();
			
		}
		
		//registra um campo mandado pelo frontend
		public function campo(){
		
			$response = array(
				"success" => false,
				"msg" => "Nenhum dado enviado!"
			);
		
			//verifica se o nome do funcionário é válido
			if(isset($_POST['nome'])){
			
				$nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
				
				$filtros = array();
				$filtros['nome'] = $nome;
				
				if($filtros['nome'] != ""){
				
					$filtros['limit'] = 1;
					$response = $this->funcionario->index($filtros);
					
					if($response['success']){
					
						$_SESSION['pedido']['funcionario'] = $nome;
						
					}else{
						
						$_SESSION['pedido']['funcionario'] = "";
						
					}
					
					$response['dados'] = $_SESSION['pedido'];
					
				}else{
					
					$_SESSION['pedido']['funcionario'] = "";
					
					$response = array(
						"success" => false,
						"msg" => "Digite o nome do funcionário!",
						"dados" => $_SESSION['pedido']
					);
					
				}
				
			}
			
			//registra a observação
			if(isset($_POST['obs'])){
			
				$obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_SPECIAL_CHARS);
				
				$_SESSION['pedido']['obs'] = $obs;
				
				$response = array(
					"success" => true,
					"msg" => "Observação inserida com sucesso!",
					"dados" => $_SESSION['pedido']
				);
				
			}
			
			//registra a forma de pagamento
			if(isset($_POST['pagamento'])){
			
				$pagamento = filter_input(INPUT_POST, 'pagamento', FILTER_SANITIZE_SPECIAL_CHARS);
				
				$_SESSION['pedido']['pagamento'] = $pagamento;
				
				$response = array(
					"success" => true,
					"msg" => "Pagamento escolhido com sucesso!",
					"dados" => $_SESSION['pedido']
				);
				
			}
			
			return $response;
		}
		
		
	}

?>
