<?php

	$response = array();

	if(isset($_GET['opcao'])){
	
		$opcao = filter_input(INPUT_GET, 'opcao', FILTER_SANITIZE_SPECIAL_CHARS);
		
		session_start();
		
		include("../app/model/Conexao.php");
		include("../app/model/Funcionario.php");
		include("../app/controller/FuncionarioController.php");
	
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
