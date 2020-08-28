# Cardápio
Aplicação de cardápio feita com php, mysql, html, css e javascript. Necessita de internet para funcionar corretamente pois utiliza as dependências externas do bootstrap e do jquery.

## Instalação
1. Crie o banco de dados copiando seu código fonte no aquivo banco.sql e definindo o padrão de caracteres para utf8_general_ci.
2. Modifique as configurações de conexão com o banco de acordo com o que você criou no arquivo app/model/Conexao.php.
3. Modifique a url raiz da aplicação de acordo com a sua máquina no arquivo js/modulos/rota.js.
4. Use o composer para instalar o auloload para utilizar os namesmaces.


## Funcionário
É necessário colocar um nome de funcionário válido para que o pedido possa ser aceito, coloque qualquer um que esteja no banco de dados.

![inicio](https://github.com/rodriguesrenato61/cardapio/blob/master/prints/print01.png)


## Adicionando prato principal

![adicionando prato](https://github.com/rodriguesrenato61/cardapio/blob/master/prints/print02.png)

 
## Substituindo prato
Cada tipo de prato possui um limite a ser colocado no pedido, para os que se limitam a somente 1 o butão de sustituir aparece no modal dos outros pratos do mesmo tipo

![substituindo prato](https://github.com/rodriguesrenato61/cardapio/blob/master/prints/print03.png)


## Removendo prato
Segue a mesma regra do substituir, mas o butão de remover só aparece no modal do prato escolhido.

![removendo prato](https://github.com/rodriguesrenato61/cardapio/blob/master/prints/print04.png)


## Adicionando acompanhamento
Para os tipos de prato com limite maior que 1 o input para setar a quantidade aparece.

![adicionando acompanhamento](https://github.com/rodriguesrenato61/cardapio/blob/master/prints/print05.png)


## Observação e forma de pagamento

![obs e forma de pagamento](https://github.com/rodriguesrenato61/cardapio/blob/master/prints/print06.png)


## Pratos escolhidos
Nessa modal podemos ver os pratos já escolhidos e podemos retirar o que não queremos mais ou trocar, além disso podemos ver o total e concluir o pedido.

![concluir pedido](https://github.com/rodriguesrenato61/cardapio/blob/master/prints/print07.png)


## Cadastrado
Caso o pedido seja registrado com sucesso no banco de dados veremos seus detalhes.

![sucesso](https://github.com/rodriguesrenato61/cardapio/blob/master/prints/print08.png)
