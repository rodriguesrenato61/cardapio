import Rota from './rota.js';

//módulo com os atributos e métodos referentes ao cardápio
export default function Cardapio(){

	const rota = new Rota();
	let local = rota.getUrl('processos');
	
	let quantPratoPrincipal = 0;
	let quantPratoFitness = 0;
	let quantAcompanhamento = 0;
	
	//seta a quantidade limite de determinado tipo de prato
	this.setQuantidade = function(tipo, quant){
		
		switch(tipo){
		
			case 'Prato Principal':
			
				quantPratoPrincipal = quant;
			
			break;
			
			case 'Prato Fitness':
			
				quantPratoFitness = quant;
			
			break;
			
			case 'Acompanhamento':
			
				quantAcompanhamento = quant;
			
			break;
			
		}
		
	}
	
	//mostra a quantidade limite de determinado tipo de prato
	this.getQuantidade = function(tipo){
		
		let retorno = 0;
		
		switch(tipo){
		
			case 'Prato Principal':
			
				retorno = quantPratoPrincipal;
			
			break;
			
			case 'Prato Fitness':
			
				retorno = quantPratoFitness;
			
			break;
			
			case 'Acompanhamento':
			
				retorno = quantAcompanhamento;
			
			break;
			
		}
		
		return retorno;
	}
	
	//carrega todas as informações registradas atualmente do pedido no backend por ajax
	this.pedido = function(){
	
		const url = local+"pedidos.php?opcao=load";
		
		//enviando uma requisição get e pegando os dados em formato json
		return fetch(url).then(function(response){
			
			return response.json();
		});
		
	}

	//carrega todos os pratos do cardápio no backend por ajax
	this.pratos = function(){
		
		const url = local+"pratos.php?opcao=index";
		
		return fetch(url).then(function(response){
			
			return response.json();
		});
	}
	
	//envia um campo (nome do funcionário, observação ou forma de pagamento para o backend por ajax
	this.funcionario = function(tipo, valor){
	
		const url = local+"funcionarios.php?opcao=campo";
		
		//colocando os dados em um FormData
		const form = new FormData();
		
		form.append(tipo, valor);
		
		//enviando uma requisição post e pegando os dados em formato json
		return fetch(url, {
			method: 'POST',
			body: form
		}).then(function(response){
			
			return response.json();
		});
		
	}
	
}

