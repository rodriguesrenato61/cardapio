<?php

	require_once("vendor/autoload.php");
	use App\Controller\PedidoController;

?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<title>Resultado</title>
	</head>
	<style type="text/css">
		
		div.container{
			width: 90%;
			margin: 0 auto;
		}
		
		li{
			font-size: 22px;
		}
		
	</style>
	<body>
		<div class="container">
		
		<?php
		
			echo("<h2><a href='index.php'>Fazer outro pedido</a></h2>");
		
			session_start();

			if(isset($_SESSION['pedido']['resultado'])){
				
				$resultado = $_SESSION['pedido']['resultado'];
				
				if($resultado['success']){
					
					$pedidoController = new PedidoController();
					
					$pedido = $pedidoController->find($resultado['id']);
					
					echo("<h1>{$pedido['msg']}</h1>");
					
					if($pedido['success']){
					
						echo("<h2>Funcionário: {$pedido['funcionario']}</h2>");
						echo("<h2>Empresa: {$pedido['empresa']}</h2>");
						echo("<h2>Data: {$pedido['data']}</h2>");
						echo("<h2>Hora: {$pedido['hora']}</h2>");
						
						$html1 = "";
						$html2 = "";
						$html3 = "";
						
						foreach($pedido['pratos'] as $prato){
							
							switch($prato['tipo']){
							
								case 'Prato Principal':
								
									$html1 .= "<li>{$prato['descricao']} -- {$prato['quantidade']} x {$prato['valor']} = R$ {$prato['total']}</li>";
								
								break;
								
								case 'Prato Fitness':
								
									$html2 .= "<li>{$prato['descricao']} -- {$prato['quantidade']} x {$prato['valor']} = R$ {$prato['total']}</li>";
								
								break;
								
								case 'Acompanhamento':
								
									$html3 .= "<li>{$prato['descricao']} -- {$prato['quantidade']} x {$prato['valor']} = R$ {$prato['total']}</li>";
								
								break;
								
							}
							
						}
						
						if($html1 != ""){
							
							$html1 = "<h2>Prato Principal</h2><ul>{$html1}</ul>";
							
						}
						
						if($html2 != ""){
						
							$html2 = "<h2>Prato Fitness</h2><ul>{$html2}</ul>";
							
						}
						
						if($html3 != ""){
						
							$html3 = "<h2>Acompanhamento</h2><ul>{$html3}</ul>";
							
						}
						
						echo("{$html1}{$html2}{$html3}");
						echo("<h2>Total: R$ {$pedido['total']}</h2>");
						
						echo("<h2>Observação: {$pedido['obs']}</h2>");
						echo("<h2>Forma de pagamento: {$pedido['pagamento']}</h2>");
						
					}
					
				}else{
				
					echo("<h1>{$resultado['msg']}</h1>");
					
				}
				
			}else{
			
				header("Location: index.php");
				
			}

		?>
	
		</div>
	</body>
</html>


