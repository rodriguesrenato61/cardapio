import Rota from './modulos/rota.js';
import Cardapio from './modulos/cardapio.js';
import Pedido from './modulos/pedido.js';

//inicializa página
$(document).ready(function(){

	carregaCampos();
	carregaPedido();
	carregaPratos();

	btnAdicionar.addEventListener('click', function(){
		cadastraPedido();
	});

	btnSubstituir.addEventListener('click', function(){
		substituiPedido();
	});

	btnRemover.addEventListener('click', function(){
		removePedido('cardapio');
	});

	btnPedidos.addEventListener('click', function(){
		btnConcluir.style.visibility = 'hidden';
		abrirModalPedidos();
	});

	btnConcluir.addEventListener('click', function(){
		concluiPedido();
	});

	btnEnviar.addEventListener('click', function(){
		btnConcluir.style.visibility = 'visible';
		abrirModalPedidos();
	});
	
});

const rota = new Rota();
const cardapio = new Cardapio();
const pedido = new Pedido();

//seletor para os campos nome do funcionário, observação e forma de pagamento
const campos = document.querySelectorAll('.campo');
//indica se o nome digitado do funcionário é válido (true) ou inválido (false) 
var funcValido = false;

const cardapioBody = document.querySelector('#cardapio-body');
const modalPrato = $('#modal-prato');
const modalPratoTitle = document.querySelector('#modal-title');
const modalPratoInfo = document.querySelector('#modal-prato-info');
const quantidade = document.querySelector('#quantidade');
const btnAdicionar = document.querySelector('#btn-adicionar');
const btnSubstituir = document.querySelector('#btn-substituir');
const btnRemover = document.querySelector('#btn-remover');
const btnCancelar = document.querySelector('#btn-cancelar');

const btnPedidos = document.querySelector('#btn-pedidos');
const modalPedidos = $('#modal-pedidos');
const pedidosBody = document.querySelector('#modal-pedidos-body');
const totalPedidos = document.querySelector('#total');
const btnConcluir = document.querySelector('#btn-concluir');

const btnEnviar = document.querySelector('#btn-enviar');

//anexa a função de envio do valor do campo ao tirar o seu foco (input) ou ser selecionado (select)
function carregaCampos(){

	campos.forEach(function(campo){
	
		const tipo = campo.getAttribute('data-campo');
	
		if(tipo != "pagamento"){
	
			campo.addEventListener('focusout', function(){
				enviaDado(tipo, this.value);	
			});
			
		}else{
			campo.addEventListener('change', function(){
				enviaDado(tipo, this.value);	
			});	
		}
		
	});
	
}

//exibe a modal com os dados do prato selecionado para adicionar ao pedido
function abrirModalPrato(tipo, prato, preco, quantPrato){
	
	modalPratoTitle.innerText = tipo;
	let html = "<p>"+prato+"</p>";
	html += "<p>R$ "+preco+"</p>";
	modalPratoInfo.innerHTML = html;
	quantidade.value = quantPrato;
	modalPrato.modal('show');
}

//fecha o modal para adicionar o prato ao pedido
function fecharModalPrato(){
	
	modalPratoTitle.innerText = "";
	modalPratoInfo.innerHTML = "";
	quantidade.value = "";
	modalPrato.modal('hide');
}

//exibe o modal dos pratos escolhidos do pedido
function abrirModalPedidos(){
	modalPedidos.modal('show');
}

//fecha o modal dos pratos escolhidos do pedido
function fecharModalPedidos(){
	modalPedidos.modal('hide');
}

//carrega todas as informações do pedido que já foram setadas
function carregaPedido(){

	//carrega os dados já registrados do pedido
	const api = cardapio.pedido();
	
	api.then(function(response){
	
		if(response.success){
			
			console.log(response.msg);
			
			if(response.controle){
				
				if(response.controle.success){
					
					//pega o controle de quantidade de cada tipo de prato
					response.controle.dados.forEach(function(controle){
					
						cardapio.setQuantidade(controle.tipo, controle.quantidade);
					
					});
				
					console.log("Prato Principal quantidade: "+cardapio.getQuantidade('Prato Principal'));
					console.log("Prato Fitness quantidade: "+cardapio.getQuantidade('Prato Fitness'));
					console.log("Acompanhamento quantidade: "+cardapio.getQuantidade('Acompanhamento'));

				}else{
				
					console.log(response.controle.msg);
					
				}
			
			}else{
			
				console.log("Controle de quantidade não carregado!");
				
			}
			
			//pega o nome do funcionário que fez o pedido se já estiver setado
			if(response.funcionario && response.funcionario != ""){
			
				campos[0].value = response.funcionario;
				funcValido = true;
			
				console.log("Funcionário válido: ");
				console.log(funcValido);
			
			}
			
			//pega a observação se já estiver setada
			if(response.obs){
			
				campos[1].value = response.obs;
			
			}
			
			//pega a forma de pagamento se já estiver setada
			if(response.pagamento){
			
				campos[2].value = response.pagamento;
			
			}
			
			//pega os pratos do cardápio que já foram escolhidos
			if(response.pratos){
				
				preenchePedidos(response.pratos);
				
			}
			
			//pega o total do pedido
			if(response.total){
			
				console.log("total: "+response.total);
				
				totalPedidos.innerHTML = "<strong>Total: </strong>R$ "+response.total;
				
			}
			
		}else{
		
			alert(response.msg);
			
		}
		
	}).catch(function(erro){
	
		alert("Erro ao recarregar informações do pedido!");
		console.log(erro);
		
	});
	
}

//carrega todos os pratos do cardápio e coloca na página
function carregaPratos(){

	//carrega todos os pratos do cardápio
	const api = cardapio.pratos();
	
	api.then(function(response){
	
		if(response.success){
			
			console.log(response.msg);
			
			const dados = response.dados;
			
			let html1 = "";
			let html2 = "";
			let html3 = "";
			
			//montando o html com os dados dos pratos para o cardápio
			dados.forEach(function(prato){
				
				switch(prato.tipo){
				
					case 'Prato Principal':
					
						html1 += "<div class='row row-prato' data-tipo='"+prato.tipo+"' data-prato='"+prato.descricao+"' data-preco='"+prato.preco+"'>";
						html1 += "<div class='col-10 row-prato-descricao'><p>"+prato.descricao+"</p></div>";
						html1 += "<div class='col-2 row-prato-preco'><p>R$ "+prato.preco+"</p></div>";
						html1 += "</div>";
					
					break;
					
					case 'Prato Fitness':
					
						html2 += "<div class='row row-prato' data-tipo='"+prato.tipo+"' data-prato='"+prato.descricao+"' data-preco='"+prato.preco+"'>";
						html2 += "<div class='col-10 row-prato-descricao'><p>"+prato.descricao+"</p></div>";
						html2 += "<div class='col-2 row-prato-preco'><p>R$ "+prato.preco+"</p></div>";
						html2 += "</div>";
					
					break;
					
					case 'Acompanhamento':
					
						html3 += "<div class='row row-prato' data-tipo='"+prato.tipo+"' data-prato='"+prato.descricao+"' data-preco='"+prato.preco+"'>";
						html3 += "<div class='col-10 row-prato-descricao'><p>"+prato.descricao+"</p></div>";
						html3 += "<div class='col-2 row-prato-preco'><p>R$ "+prato.preco+"</p></div>";
						html3 += "</div>";
					
					break;
					
				}
				
				
			});
			
			if(html1 != ""){
	
				html1 = "<h2>Prato Principal</h2>"+html1;
				
			}
			
			if(html2 != ""){
			
				html2 = "<h2>Prato Fitness</h2>"+html2;
				
			}
			
			if(html3 != ""){
			
				html3 = "<h2>Acompanhamento</h2>"+html3;
				
			}
			
			//adicionando o html do cardápio na página
			cardapioBody.innerHTML = html1+html2+html3;
			
			//selecionando os elementos criados com os dados dos pratos no cardápio
			const selectPrato = document.querySelectorAll('.row-prato');
	
			//anexando o evento de click de cada prato do cardápio a abertura do modal de adição do pedido
			selectPrato.forEach(function(prato){
			
				prato.addEventListener('click', function(){
				
					//pegando os dados do prato selecionado para colocar na modal
					const quantTipo = cardapio.getQuantidade(this.getAttribute('data-tipo'));
					const tipo = this.getAttribute('data-tipo');
					const descricao = this.getAttribute('data-prato');
					const preco = this.getAttribute('data-preco');
					let quantPrato = "";
					
					//verifica se o prato selecionado já está inserido no pedido
					const pedidoCadastrado = pedido.estaCadastrado(descricao);
					
					if(pedidoCadastrado){
					
						quantPrato = pedidoCadastrado.quantidade;//pega a quantidade desse prato que já está registrado no pedido
					
						//se a quantidade máxima desse tipo de prato for maior que 1
						if(quantTipo > 1){
						
							quantidade.style.display = 'block';//input da quantidade fica exposto
							btnAdicionar.style.display = 'block';//butão de adicionar fica exposto
							btnSubstituir.style.display = 'none';//butão de substituir fica oculto
							btnRemover.style.display = 'none';//butão de remover fica oculto
							
						}else{
						
							quantidade.style.display = 'none';//input da quantidade fica oculto
							btnAdicionar.style.display = 'none';//butão de adicionar fica oculto
							btnSubstituir.style.display = 'none';//butão de substituir fica oculto
							btnRemover.style.display = 'block';//butão de remover fica exposto
						
						}
					
					}else{
						//se o prato selecionado não está inserido no pedido
						
						if(quantTipo > 1){
							
							btnAdicionar.style.display = 'block';//butão de adicionar fica exposto
							quantidade.style.display = 'block';//input da quantidade fica exposto
							btnSubstituir.style.display = 'none';//butão de substituir fica oculto
							btnRemover.style.display = 'none';//butão de remover fica oculto
							
						}else{
							
							btnRemover.style.display = 'none';//butão de remover fica oculto
							
							//se a quantidade total de pratos desse tipo for 1 e esse limite já foi alcançado
							if(pedido.quantTotal(tipo) == 1){
							
								btnAdicionar.style.display = 'none';
								btnSubstituir.style.display = 'block';
								
							}else{
								
								btnSubstituir.style.display = 'none';
								btnAdicionar.style.display = 'block';
								
							}
							
							quantidade.style.display = 'none';//input da quantidade fica oculto
							quantPrato = 1;//fica subentendido que a quantidade é 1
	
						}
												
					}
				
					//pega os dados do prato selecionado
					pedido.set(tipo, descricao, preco, quantPrato);
					
					//exibe o modal com os dados do prato selecionado
					abrirModalPrato(tipo, descricao, preco, quantPrato);
					
					
				});
				
			});
			
			
		}else{
		
			alert(response.msg);
			
			if(response.erro){
			
				console.log(response.erro);
			
			}
			
		}
		
	}).catch(function(erro){
		
		alert("Erro ao carregar os pratos do cardápio!");
		console.log(erro);
		
	});
	
}

//carrega todos os pratos do pedido que o cliente já escolheu
function preenchePedidos(pratos){
	
	console.log("Pratos carregados!");
	
	//reinicia o array com os pratos escolhidos			
	pedido.iniciar();
	
	let html1 = "";
	let html2 = "";
	let html3 = "";
	
	//monta o html com os dados dos pratos escolhidos para a modal de pedidos
	pratos.forEach(function(prato){
		
		switch(prato.tipo){
		
			case 'Prato Principal':
			
				html1 += "<div class='row row-pedido' data-tipo='"+prato.tipo+"' data-prato='"+prato.descricao+"' data-preco='"+prato.preco+"'>";
				html1 += "<div class='col-7 row-pedido-descricao'><p>"+prato.descricao+"</p></div>";
				html1 += "<div class='col-4 row-pedido-preco'><p>"+prato.quantidade+" X "+prato.preco+" = R$ "+prato.total+"</p></div>";
				html1 += "<div class='col-1 row-pedido-remover'><a href='' class='remover-pedido' data-prato='"+prato.descricao+"' data-tipo='"+prato.tipo+"' data-preco='"+prato.preco+"' data-quantidade='"+prato.quantidade+"'><img src='assets/icons/trash-2.svg'></a></div>";
				html1 += "</div>";
			
			break;
			
			case 'Prato Fitness':
			
				html2 += "<div class='row row-pedido' data-tipo='"+prato.tipo+"' data-prato='"+prato.descricao+"' data-preco='"+prato.preco+"'>";
				html2 += "<div class='col-7 row-pedido-descricao'><p>"+prato.descricao+"</p></div>";
				html2 += "<div class='col-4 row-pedido-preco'><p>"+prato.quantidade+" X "+prato.preco+" = R$ "+prato.total+"</p></div>";
				html2 += "<div class='col-1 row-pedido-remover'><a href='' class='remover-pedido' data-prato='"+prato.descricao+"' data-tipo='"+prato.tipo+"' data-preco='"+prato.preco+"' data-quantidade='"+prato.quantidade+"'><img src='assets/icons/trash-2.svg'></a></div>";
				html2 += "</div>";
			
			break;
			
			case 'Acompanhamento':
			
				html3 += "<div class='row row-pedido' data-tipo='"+prato.tipo+"' data-prato='"+prato.descricao+"' data-preco='"+prato.preco+"'>";
				html3 += "<div class='col-7 row-pedido-descricao'><p>"+prato.descricao+"</p></div>";
				html3 += "<div class='col-4 row-pedido-preco'><p>"+prato.quantidade+" X "+prato.preco+" = R$ "+prato.total+"</p></div>";
				html3 += "<div class='col-1 row-pedido-remover'><a href='' class='remover-pedido' data-prato='"+prato.descricao+"' data-tipo='"+prato.tipo+"' data-preco='"+prato.preco+"' data-quantidade='"+prato.quantidade+"'><img src='assets/icons/trash-2.svg'></a></div>";
				html3 += "</div>";
			
			break;
			
		}
		
		//adicionando o prato ao array de pratos escolhidos
		pedido.add(prato.tipo, prato.descricao, prato.preco, prato.quantidade);
		
	});
	
	if(html1 != ""){
	
		html1 = "<h3>Prato Principal</h3>"+html1;
		
	}
	
	if(html2 != ""){
	
		html2 = "<h3>Prato Fitness</h3>"+html2;
		
	}
	
	if(html3 != ""){
	
		html3 = "<h3>Acompanhamento</h3>"+html3;
		
	}
	
	//adicionando o html dos pratos escolhidos ao modal de pedidos na página
	pedidosBody.innerHTML = html1+html2+html3;
	
	//seleciona os links com o ícone de exclusão de cada prato escolhido
	let pedidoRemover = document.querySelectorAll('.remover-pedido');
	
	//anexando o evento de click de cada ícone da lixeira a função de remoção de cada prato do pedido
	pedidoRemover.forEach(function(pedidoRemove){
	
		pedidoRemove.addEventListener('click', function(event){
		
			//barrando a ação padrão do click de um link
			event.preventDefault();
			
			//pegando os dados do prato escolhido
			const descricao = this.getAttribute('data-prato');
			const tipo = this.getAttribute('data-tipo');
			const preco = this.getAttribute('data-preco');
			const quant = this.getAttribute('data-quantidade');
			
			if(confirm("Você tem certeza de que deseja remover este pedido?")){
			
				pedido.set(tipo, descricao, preco, quant);
				
				removePedido('pedidos');
				
			}
		
		});
		
	});
	
	console.log("Prato Principal: "+pedido.quantPratoPrincipal);
	console.log("Prato Fitness: "+pedido.quantPratoFitness);
	console.log("Acompanhamento: "+pedido.quantAcompanhamento);
	
	console.log(pedido.pedidos);
			
}

//envia um campo (nome do funcionário, observação ou forma de pagamento) para ser registrado
function enviaDado(tipo, valor){

	//envia o valor do campo para ser registrado
	const api = cardapio.funcionario(tipo, valor);
	
	api.then(function(response){
		
		console.log(response.msg);
		
		//verifica se o nome do funcionário é válido
		if(response.dados.funcionario != ""){
		
			funcValido = true;
			campos[0].style.border = '';
			
		}else{
			
			funcValido = false;
			campos[0].style.border = '1px solid red';
		}
		
		if(funcValido){
			console.log("Funcionário válido!");
		}else{
			console.log("Funcionário inválido!");
		}
			
	}).catch(function(erro){
	
		alert("Erro ao enviar campo!");
		console.log(erro);
		
	});
	
}

//envia um novo prato para colocar no pedido
function cadastraPedido(){
	
	//verifica se a quantidade foi setada
	if(quantidade.value != ""){
		
		const quant = parseInt(quantidade.value);
		
		if(quant > 0){
			
			pedido.quantidade = quant;
				
			//envia o prato para ser cadastrado no pedido			
			const api = pedido.cadastrar();
			
			api.then(function(response){
			
				if(response.success){
					
					//recarrega os pratos escolhidos do pedido
					preenchePedidos(response.pedidos);
					
					console.log("total: "+response.total);
					
					totalPedidos.innerHTML = "<strong>Total: </strong>R$ "+response.total;
					
					fecharModalPrato();
					
					alert(response.msg);
					
				}else{
					
					alert(response.msg);
				
				}
				
			}).catch(function(erro){
			
				alert("Erro ao registrar esse prato no pedido!");
				console.log(erro);
				
			});
			
				
		}
		
	}
	
}

//substitui um prato escolhido por outro do mesmo tipo
function substituiPedido(){
	
	if(confirm("Você só pode pedir no máximo 1 prato para o "+pedido.tipo+", tem certeza de que deseja substituir o prato escolhido por este?")){
		
		//envia os dados do prato para a substituição
		const api = pedido.substituir();
		
		api.then(function(response){
		
			if(response.success){
				
				preenchePedidos(response.pedidos);
				
				totalPedidos.innerHTML = "<strong>Total: </strong>R$ "+response.total;
				
				fecharModalPrato();
				
				alert(response.msg);
				
			}else{
			
				alert(response.msg);
				
			}
			
		}).catch(function(erro){
		
			alert("Erro ao substituir o prato esolhido anteriormente!");
			console.log(erro);
			
		});
		
	}else{
	
		fecharModalPrato();
		
	}

	
	
}

//remove um prato escolhido do pedido
function removePedido(lugar){

	//envia os dados do prato a ser removido
	const api = pedido.remover();
	
	api.then(function(response){
	
		if(response.success){
			
			preenchePedidos(response.pedidos);
			
			totalPedidos.innerHTML = "<strong>Total: </strong>R$ "+response.total;
			
			//verifica se está excluindo o prato pelo cardápio ou pelos pedidos escolhidos
			if(lugar == 'cardapio'){
				fecharModalPrato();
			}
			
			alert(response.msg);
			
		}else{
		
			alert(response.msg);
			
		}
		
	}).catch(function(erro){
	
		alert("Erro ao remover este prato do pedido!");
		console.log(erro);
		
	});
	
}

//conclui e registra o pedido
function concluiPedido(){

	//verifica se o nome do funcionário é válido
	if(funcValido){
		
		//verifica se existe algum prato esolhido no pedido
		if(pedido.pedidos.length > 0){
			
			//envia a requisição de conclusão do pedido
			const api = pedido.concluir();
			
			api.then(function(response){
			
				alert(response.msg);
				
				window.location.href = rota.getUrl('resultado.php');
				
			}).catch(function(erro){
			
				alert("Erro ao concluir pedido!");
				console.log(erro);
			});
			
		}else{
		
			alert("Você precisa fazer pelo menos um pedido!");
			
		}
		
	}else{
	
		alert("Coloque um nome de funcionário válido!");
		campos[0].style.border = '1px solid red';
		
	}
	
}
