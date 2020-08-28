<?php

	namespace App\Controller;
	
	use App\Model\Prato;

	class PratoController{
		
		private $prato;
		
		public function __construct(){
		
			$this->prato = new Prato();
			
		}
		
		//retorna os pratos armazenados no banco de dados
		public function index(){
		
			$response = $this->prato->index();
			
			return $response;
		}
		
	}

?>
