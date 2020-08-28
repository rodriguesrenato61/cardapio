<?php

	$response = array();

	if(isset($_GET['opcao'])){
	
		$opcao = filter_input(INPUT_GET, 'opcao', FILTER_SANITIZE_SPECIAL_CHARS);
		
		session_start();
		
		
		use App\Controller\FuncionarioController;
	
		$funcionarioController = new FuncionarioController();
		
		switch($opcao){
			
			case 'campo':
			
				$response = $funcionarioController->campo();
			
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
