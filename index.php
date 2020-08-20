<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		
		<!--importando a folha de estilos do bootstrap-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
		
		<link rel="stylesheet" href="css/modal.css">
		<link rel="stylesheet" href="css/styles.css">
		 <!--importando o jquery-->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

		<!--importando javascript do bootstrap-->
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
				
		<title>Cardápio</title>
	</head>
	<body>
		
		<!-- Modal -->
			<div id="modal-prato" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
			  <div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="modal-title">Título do modal</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div id="modal-body" class="modal-body">
					  <div id="modal-prato-info">
					  </div>
					  <div id="modal-input" class="form-group">
						<input type="number" class="form-control" id="quantidade" placeholder="quantidade">
					  </div>
				  </div>

				  <div class="modal-footer">
					<button type="button" id="btn-adicionar" class="btn btn-primary">Adicionar</button>
					<button type="button" id="btn-substituir" class="btn btn-success">Substituir</button>
					<button type="button" id="btn-remover" class="btn btn-danger">Remover</button>
					<button type="button" id="btn-cancelar" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
				  </div>
				</div>
			  </div>
			</div>
		
			<div id="modal-pedidos" class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div id="modal-pedidos-head" class="modal-header bg-light">
						<h5 id="modal-pedidos-title">Pedidos</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
					  <span aria-hidden="true">&times;</span>
					</button>
				  </div>
				  <div id="modal-pedidos-body" class="modal-body">
					
				  </div>
				  <div id="modal-bottom-pedidos" class="modal-footer">
					<div id="total"></div>
					<button type="button" id="btn-concluir" class="btn btn-primary">Concluir</button>
				  </div>
					
				</div>
			  </div>
			</div>
		
		<div class="container">
			<div id="form-pedido">
			
				<div id="nome-funcionario" class="card bg-light">
					<div class="card-body">
						<div class="form-group">
							<h2 class="title-campo"><label for="funcionario">Nome</label></h2>
							<input type="text" class="form-control campo" id="funcionario" data-campo="nome" placeholder="Nome do funcionário" maxlength="70">
						</div>
					</div>
				</div>

			  
			  <div id="cardapio" class="card">
				  <div class="card-header" id="cardapio-head">
					<h2>Cardápio</h2>
				  </div>
				  <div id="cardapio-body" class="card-body bg-light">

				   </div>
				</div> <!-- class cardapio -->
				
				<button type="button" id="btn-pedidos" class="btn btn-primary">Pedidos</button>
			  
			<div id="container-obs" class="card bg-light">
			  <div class="form-group container-campo">
				<h2 class="title-campo2"><label for="obs">Observação</label></h2>
				<p id="p-obs">Alguma observação sobre seu pedido?</p>
				<textarea class="form-control campo" id="obs" data-campo="obs" placeholder="Sua resposta" maxlength="100"></textarea>
			  </div>
			</div>
			<div id="container-pagamento" class="card bg-light">
			  <div class="form-group container-campo">
				<h2 class="title-campo2"><label for="pagamento">Forma de pagamento</label></h2>
				<select class="form-control campo" id="pagamento" data-campo="pagamento">
					<option value="Mensalista">Mensalista</option>
					<option value="Dinheiro">Dinheiro</option>
				</select>
			  </div>
			 </div>
			  <button type="submit" id="btn-enviar" class="btn btn-primary">Enviar</button>
			</div>
		</div>
		
		<script type="module" src="js/scripts.js"></script>
	</body>
</html>
