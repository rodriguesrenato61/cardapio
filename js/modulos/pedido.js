import Rota from './rota.js';

export default function Pedido(){
	
	this.tipo;
	this.prato;
	this.preco; 
	this.quantidade;
	this.pedidos = new Array();
	this.quantPratoPrincipal = 0;
	this.quantPratoFitness = 0;
	this.quantAcompanhamento = 0;
	
	const rota = new Rota();
	const local = rota.getUrl('processos');
	
	//seta os dados do pedido escolhido
	this.set = function(tipo, prato, preco, quantidade){
		this.tipo = tipo;
		this.prato = prato;
		this.preco = preco;
		this.quantidade = quantidade;
	}
	
	//envia o cadastro de um prato escolhido para o backend por ajax
	this.cadastrar = function(){
	
		const url = local+"pedidos.php?opcao=inserir";
		
		//colocando os dados do prato escolhido em um FormData
		const form = new FormData();
		
		form.append('tipo', this.tipo);
		form.append('prato', this.prato);
		form.append('preco', this.preco);
		form.append('quantidade', this.quantidade);
		
		//fazendo uma requisição post e pegando os dados em formato json
		return fetch(url, {
			method: 'POST',
			body: form
		}).then(function(response){
			
			return response.json();
		});
		
	}
	
	//envia a requisiçao de substituição de um prato escolhido para o backend por ajax
	this.substituir = function(){
	
		const url = local+"pedidos.php?opcao=substituir";
		
		const form = new FormData();
		
		form.append('tipo', this.tipo);
		form.append('prato', this.prato);
		form.append('preco', this.preco);
		
		return fetch(url, {
			method: 'POST',
			body: form
		}).then(function(response){
			
			return response.json();
		});
		
	}
	
	//envia a requisição de conclusão do pedido para o backend por ajax
	this.concluir = function(){
	
		const url = local+"pedidos.php?opcao=concluir";
		
		//fazendo uma requisição get e pegando a resposta em formato json
		return fetch(url).then(function(response){
			
			return response.json();
		});
		
	}
	
	//envia a requisição de remoção de um prato do pedido para o backend por ajax
	this.remover = function(){
	
		const url = local+"pedidos.php?opcao=remover";
		
		const form = new FormData();
	
	
		form.append('prato', this.prato);
		
		return fetch(url, {
			method: 'POST',
			body: form
		}).then(function(response){
			
			return response.json();
		});
		
	}
	
	//reinicia o array de pratos escolhidos e zera a quantidade de cada tipo
	this.iniciar = function(){
		this.pedidos = new Array();
		this.quantPratoPrincipal = 0;
		this.quantPratoFitness = 0;
		this.quantAcompanhamento = 0;
	}
	
	//adiciona um prato ao array de pratos escolhidos e incrementa a quantidade do seu tipo
	this.add = function(tipo, prato, preco, quantidade){
		
		const novo = {
			tipo: tipo,
			prato: prato,
			preco: preco,
			quantidade: parseInt(quantidade)
		};
		
		this.pedidos.push(novo);
		
		switch(tipo){
		
			case 'Prato Principal':
			
				this.quantPratoPrincipal += novo.quantidade;
			
			break;
			
			case 'Prato Fitness':
			
				this.quantPratoFitness += novo.quantidade;
			
			break;
			
			case 'Acompanhamento':
			
				this.quantAcompanhamento += novo.quantidade;
			
			break;
			
		}
		
		
	}
	
	//mostra a quantidade total de pratos escolhidos do mesmo tipo do prato selecionado
	this.getQuantTotal = function(){
		
		let retorno = false;
		
		switch(this.tipo){
		
			case 'Prato Principal':
			
				retorno = this.quantPratoPrincipal;
			
			break;
			
			case 'Prato Fitness':
			
				retorno = this.quantPratoFitness;
			
			break;
			
			case 'Acompanhamento':
			
				retorno = this.quantAcompanhamento;
			
			break;
			
		}
		
		return retorno;
	}
	
	//mostra a quantidade total de pratos escolhidos de determinado tipo
	this.quantTotal = function(tipo){
		
		let retorno = false;
		
		switch(tipo){
		
			case 'Prato Principal':
			
				retorno = this.quantPratoPrincipal;
			
			break;
			
			case 'Prato Fitness':
			
				retorno = this.quantPratoFitness;
			
			break;
			
			case 'Acompanhamento':
			
				retorno = this.quantAcompanhamento;
			
			break;
			
		}
		
		return retorno;
	}
	
	//verifica se um determinado prato já foi escolhido
	this.estaCadastrado = function(prato){
		
		let retorno = false;
		
		for(let i = 0; i < this.pedidos.length; i++){
			
			if(this.pedidos[i].prato == prato){
			
				retorno = this.pedidos[i];
				
				break;
			}
			
		}
		
		return retorno;
	}
	
	//mostra os dados do pedido escolhido
	this.mostrar = function(){
		console.log("Prato selecionado");
		console.log("tipo: "+this.tipo);
		console.log("descrição: "+this.prato);
		console.log("preço: "+this.preco);
		console.log("quantidade: "+this.quantidade);
	}
	
}
