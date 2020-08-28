<?php
	

	namespace App\Controller;
	
	use App\Model\Pedido;

	class PedidoController{
		
		private $pedido;
		
		public function __construct(){
		
			$this->pedido = new Pedido();
			
		}
		
		public function find($id){
		
			$response = $this->pedido->find($id);
			
			return $response;
		}
		
		//carrega os dados registrados do pedido iniciado pelo funcionário
		public function load(){
			
			if(isset($_SESSION['pedido'])){
				
				$_SESSION['pedido']['msg'] = "Continuando pedido!";
				$_SESSION['pedido']['total'] = $this->calculaTotalGeral();
				
				//se o pedido já foi concluído ele será resetado
				if(isset($_SESSION['pedido']['resultado'])){
				
					$controle = $this->pedido->getControle();
			
					$_SESSION['pedido'] = array(
						"success" => true,
						"msg" => "Iniciando pedido!",
						"controle" => $controle,
						"funcionario" => "",
						"pratos" => array(),
						"total" => "0,00",
						"obs" => "",
						"pagamento" => "Mensalista"
					);
					
				}
				
				
			}else{
				
				//inicia o pedido
				$controle = $this->pedido->getControle();
			
				$_SESSION['pedido'] = array(
					"success" => true,
					"msg" => "Iniciando pedido!",
					"controle" => $controle,
					"funcionario" => "",
					"pratos" => array(),
					"total" => "0,00",
					"obs" => "",
					"pagamento" => "Mensalista"
				);
				
			}
			
			$response = $_SESSION['pedido'];
			
			return $response;		
		}
		
		//registra um prato no pedido
		public function inserir(){
		
			if(isset($_POST['tipo']) && isset($_POST['prato']) && isset($_POST['preco']) && isset($_POST['quantidade'])){
					
				//pegando os dados do prato enviados pelo frontend por reqquisição post
				$prato = filter_input(INPUT_POST, 'prato', FILTER_SANITIZE_SPECIAL_CHARS);
				$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
				$preco = filter_input(INPUT_POST, 'preco', FILTER_SANITIZE_SPECIAL_CHARS);
				$quantidade = filter_input(INPUT_POST, 'quantidade', FILTER_SANITIZE_SPECIAL_CHARS);
				$quantidade = intval($quantidade);
				$pratos = $_SESSION['pedido']['pratos'];
				
				$j = count($pratos);
				$achou = false;
				//pegando a quantidade limite que pode ser pedida para esse tipo de prato
				$quantControle = $this->getQuantidadeControle($tipo);
				$msg = "";
				
				//indica se o prato foi registrado ou não
				$success = false;
				
				for($i = 0; $i < $j; $i++){
					
					//se esse prato já estiver registrado no pedido sua quantidade será atualizada
					if($pratos[$i]['descricao'] == $prato){
						
						$achou = true;
						$quantTotal = $this->calculaQuantidade($tipo) - $pratos[$i]['quantidade'] + $quantidade;
						
						//só atualiza se a quantidade total desse tipo de prato estiver dentro do limite
						if($quantTotal <= $quantControle){
							
							$_SESSION['pedido']['pratos'][$i]['quantidade'] = $quantidade; 
							$_SESSION['pedido']['pratos'][$i]['total'] = $this->calculaTotal($quantidade, $preco);
							$success = true;
							$msg = "Pedido atualizado com sucesso!";
							
						}else{
						
							$success = false;
							$msg = "Você só pode pedir no máximo {$quantControle} pratos para o {$tipo}!";
							
						}
									
						break;
						
					}
					
				}
				
				//se esse prato não estiver registrado no pedido ele será inserido
				if(!$achou){
					
					$quantTotal = $this->calculaQuantidade($tipo) + $quantidade;
						
					if($quantTotal <= $quantControle){
						
						$_SESSION['pedido']['pratos'][] = array(
							"descricao" => $prato,
							"tipo" => $tipo,
							"preco" => $preco,
							"quantidade" => $quantidade,
							"total" => $this->calculaTotal($quantidade, $preco)
						);
						
						$success = true;
						$msg = "Pedido cadastrado com sucesso!";
						
					}else{
					
						$success = false;
						$msg = "Você só pode pedir no máximo {$quantControle} pratos para o {$tipo}!";
						
					}
					
						
				}
				
				//se ele for trocado ou inserido retorna sucesso (true)
				if($success){
					
					$response = array(
						"success" => $success,
						"msg" => $msg,
						"pedidos" => $_SESSION['pedido']['pratos'],
						"total" => $this->calculaTotalGeral()
					);
					
				}else{
					
					$response = array(
							"success" => $success,
							"msg" => $msg
						);
					
				}
				
			}else{
			
				$response = array(
					"success" => false,
					"msg" => "Envie os dados do pedido corretamente!"
				);
				
			}
			
			return $response;
		}
		
		//remove um determinado prato do pedido
		public function remover(){
		
			if(isset($_POST['prato'])){
					
				$prato = filter_input(INPUT_POST, 'prato', FILTER_SANITIZE_SPECIAL_CHARS);
				
				$pratos = $_SESSION['pedido']['pratos'];
				
				$j = count($pratos);
				$msg = "";
				$achou = false;
				
				for($i = 0; $i < $j; $i++){
					
					if($pratos[$i]['descricao'] == $prato){
					
						$pratos[$i]['remover'] = true;
						$achou = true;
						break;
						
					}
					
				}
				
				if($achou){
					
					$novo = array();
					
					foreach($pratos as $prato){
					
						if(!isset($prato['remover'])){
						
							$novo[] = $prato;
						
						}
						
					}
					
					$_SESSION['pedido']['pratos'] = $novo;
					$msg = "Pedido removido com sucesso!"; 
						
				}else{
				
					$msg = "Pedido não encontrado!";
					
				}
				
				$response = array(
					"success" => true,
					"msg" => $msg,
					"pedidos" => $_SESSION['pedido']['pratos'],
					"total" => $this->calculaTotalGeral()
				);
				
			}else{
			
				$response = array(
					"success" => false,
					"msg" => "Envie os dados de remoção do pedido corretamente!"
				);
				
			}
			
			return $response;
		}
		
		//substitui um prato por outro somente se o limite do seu tipo for 1 
		public function substituir(){
		
			if(isset($_POST['tipo']) && isset($_POST['prato']) && isset($_POST['preco'])){
					
				$tipo = filter_input(INPUT_POST, 'tipo', FILTER_SANITIZE_SPECIAL_CHARS);
				$prato = filter_input(INPUT_POST, 'prato', FILTER_SANITIZE_SPECIAL_CHARS);
				$preco = filter_input(INPUT_POST, 'preco', FILTER_SANITIZE_SPECIAL_CHARS);
				$pratos = $_SESSION['pedido']['pratos'];
				
				$j = count($pratos);
				$msg = "";
				$achou = false;
				
				for($i = 0; $i < $j; $i++){
					
					if($pratos[$i]['tipo'] == $tipo){
					
						$pratos[$i]['descricao'] = $prato;
						$pratos[$i]['preco'] = $preco;
						$pratos[$i]['total'] = $this->calculaTotal(1, $preco);

						$achou = true;
						break;
						
					}
					
				}
				
				if($achou){
					
					$_SESSION['pedido']['pratos'] = $pratos;
					$msg = "Pedido substituído com sucesso!"; 
						
				}else{
				
					$msg = "Pedido não encontrado!";
					
				}
				
				$response = array(
					"success" => true,
					"msg" => $msg,
					"pedidos" => $_SESSION['pedido']['pratos'],
					"total" => $this->calculaTotalGeral()
				);
				
			}else{
			
				$response = array(
					"success" => false,
					"msg" => "Envie os dados do pedido corretamente!"
				);
				
			}
			
			return $response;
		}
		
		//conclui um pedido
		public function concluir(){
		
			//registra o pedido no banco de dados
			$response = $this->pedido->insert($_SESSION['pedido']);
				
			$_SESSION['pedido']['resultado'] = $response;
			
			return $response;
		}
		
		//calcula a quantidade de pratos de determinado tipo que já foram registrados no pedido
		public function calculaQuantidade($tipo){
		
			$quantidade = 0;
			
			$pratos = $_SESSION['pedido']['pratos'];
		
			foreach($pratos as $prato){
			
				if($prato['tipo'] == $tipo){
				
					$quantidade += intval($prato['quantidade']);
					
				}
				
			}
			
			return $quantidade;
		}
		
		//pega a quantidade limite de determinado tipo de prato
		public function getQuantidadeControle($tipo){
		
			$controle = $_SESSION['pedido']['controle']['dados'];
			
			$quantidade = 0;
			
			$j = count($controle);
			
			for($i = 0; $i < $j; $i++){
			
				if($controle[$i]['tipo'] == $tipo){
				
					$quantidade = $controle[$i]['quantidade'];
					
				}
				
			}
			
			return $quantidade;
		}
		
		//calcula o total de um prato
		public function calculaTotal($quantidade, $preco){
		
			$total = $quantidade * $this->pedido->convertPreco($preco);
			
			//retorna o valor total com a vírgula no lugar do ponto e com duas casas decimais como se fosse um valor monetário
			$total = number_format((float) $total, 2, ',', '.');
			
			return $total;
		}
		
		//calcula o total geral do pedido
		public function calculaTotalGeral(){
		
			$pratos = $_SESSION['pedido']['pratos'];
		
			$soma = 0;
		
			foreach($pratos as $prato){
				
				$soma += $this->pedido->convertPreco($prato['total']);
				
			}
			
			$total = number_format((float) $soma, 2, ',', '.');
				
			return $total;
		}
		
	}

?>
