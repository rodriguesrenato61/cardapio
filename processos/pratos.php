<?php

	require_once("../vendor/autoload.php");
	use App\Controller\PratoController;

	$response = array();
	
	if(isset($_GET['opcao'])){
		
		$opcao = filter_input(INPUT_GET, 'opcao', FILTER_SANITIZE_SPECIAL_CHARS);
		
		$pratoController = new PratoController();
		
		switch($opcao){
		
			case 'index':
			
				$response = $pratoController->index();
			
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
