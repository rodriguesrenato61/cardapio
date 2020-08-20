//módulo para trabalhar com as urls do projeto
export default function Rota(){
	
	//url da raiz do projeto para ser utilizada nas requisições ajax
	let url = 'http://localhost/projetos/cardapio/';
	
	this.getUrl = function(tipo){
		
		let retorno = url;
		
		switch(tipo){
		
			case 'processos':
			
				retorno += "processos/";
			
			break;
			
			default:
			
				retorno += tipo;
			
			break;
			
		}
		
		return retorno;
	}
	
	
}
