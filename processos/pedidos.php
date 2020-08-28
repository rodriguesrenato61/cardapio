<?php

	require_once("../vendor/autoload.php");

	use App\Controller\PedidoController;

	$response = array();

	if(isset($_GET['opcao'])){
		
		session_start();
		
		$pedidoController = new PedidoController();
		
		//unset($_SESSION['pedido']);
		
		$opcao = filter_input(INPUT_GET, 'opcao', FILTER_SANITIZE_SPECIAL_CHARS);
		
		switch($opcao){
		
			case 'load':
			
				$response = $pedidoController->load();
			
			break;
			
			case 'inserir':
			
				$response = $pedidoController->inserir();
			
			break;
			
			case 'remover':
			
				$response = $pedidoController->remover();
			
			break;
			
			case 'substituir':
			
				$response = $pedidoController->substituir();
			
			break;
			
			case 'concluir':
			
				$response = $pedidoController->concluir();
			
			break;
			
			
		}
		
	}else{
		
		$response = array(
			"success" => false,
			"msg" => "Nenhum dado enviado!"
		);
		
	}
	
	echo json_encode($response);

?>
