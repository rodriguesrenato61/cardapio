CREATE TABLE empresas(
	id INTEGER NOT NULL AUTO_INCREMENT,
	nome VARCHAR(50) NOT NULL,
	PRIMARY KEY(id)
);

INSERT INTO empresas(nome)
	VALUES('AÇO MARANHÃO'),('DIMENSÃO'),('DIRETORIA');

CREATE TABLE funcionarios(
	id INTEGER NOT NULL AUTO_INCREMENT,
	nome VARCHAR(70) NOT NULL,
	fk_empresa INTEGER NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(fk_empresa) REFERENCES empresas(id)
);

/*view para trazer os dados dos funcionários*/
CREATE VIEW vw_funcionarios AS SELECT f.id, f.nome AS funcionario, f.fk_empresa, e.nome AS empresa FROM funcionarios AS f
INNER JOIN empresas AS e ON f.fk_empresa = e.id; 

INSERT INTO funcionarios(nome, fk_empresa)
	VALUES('Alberto dos Santos', 1),('Carlos Augusto', 1),
	('Valéria Lira', 1),('Bianca Lobato', 1),('Eduardo Ramos', 2),
	('Raimunda Chagas', 2),('Fernando Sousa', 2),('Larissa Garcia', 2),
	('Osvaldo Nascimento', 3),('Paulo Ferreira', 3),('Marcos Augusto', 3),
	('Carla Morgado', 3);

CREATE TABLE pratos(
	id INTEGER NOT NULL AUTO_INCREMENT,
	descricao VARCHAR(250) NOT NULL,
	preco DOUBLE NOT NULL,
	tipo VARCHAR(30) NOT NULL,
	PRIMARY KEY(id)
);

INSERT INTO pratos(descricao, preco, tipo)
	VALUES('Estrogonofre Cremoso de Frango', 15.00, 'Prato Principal'),('Escondidinho de Frango Cremoso', 15.50, 'Prato Principal'),('Tortinha de Carne Seca', 12.00, 'Prato Principal'),('Frango a Passarinho', 13.50, 'Prato Principal'), 
	('Frango Grelhado (Arroz integral, feijão, salada de massa fusilli integral, salada verde e chips de batata doce)', 09.00, 'Prato Fitness'),
	('Salada a lá predileta com frango trinchado, mix de alface, cenoura, pepino, tomate, manga, chuchu, milho, uvas passas, e chitos de batata doce', 10.00, 'Prato Fitness'),
	('Arroz a Grega', 2.50, 'Acompanhamento'),('Arroz Branco', 2.00, 'Acompanhamento'),('Arroz Integral', 2.00, 'Acompanhamento'),('Arroz Primavera', 2.75, 'Acompanhamento'),
	('Arroz Baião de Dois', 3.00, 'Acompanhamento'),('Farofa Simples', 1.50, 'Acompanhamento'),('Feijão Mulato', 3.50, 'Acompanhamento'),('Feijão Preto', 3.50, 'Acompanhamento'),
	('Legumes a Vapor', 2.00, 'Acompanhamento'),('Purê de Batata', 1.50, 'Acompanhamento'),('Macarrão', 2.50, 'Acompanhamento'),('Salada Colorida', 3.00, 'Acompanhamento'); 

CREATE TABLE funcionarios_pedidos(
	id INTEGER NOT NULL AUTO_INCREMENT,
	fk_funcionario INTEGER NOT NULL,
	obs VARCHAR(100),
	pagamento VARCHAR(30) NOT NULL,
	dt_registro DATETIME NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(fk_funcionario) REFERENCES funcionarios(id)
);


CREATE TABLE pedidos(
	id INTEGER NOT NULL AUTO_INCREMENT,
	descricao_prato VARCHAR(250) NOT NULL,
	tipo_prato VARCHAR(30) NOT NULL,
	quantidade INTEGER NOT NULL,
	valor DOUBLE NOT NULL,
	fk_funcionario_pedido INTEGER NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY(fk_funcionario_pedido) REFERENCES funcionarios_pedidos(id)
); 

DELIMITER $$

/*procedure para inserir um pedido pelo banco*/
CREATE PROCEDURE insert_pedido(id_prato INTEGER, par_quantidade INTEGER, fk_fp INTEGER)
BEGIN

	SET @descricao_prato = (SELECT descricao FROM pratos WHERE id = id_prato);
	
	SET @valor = (SELECT preco FROM pratos WHERE id = id_prato);
	
	SET @tipo = (SELECT tipo FROM pratos WHERE id = id_prato);
	
	INSERT INTO pedidos(descricao_prato, tipo_prato, quantidade, valor, fk_funcionario_pedido)
		VALUES(@descricao_prato, @tipo, par_quantidade, @valor, fk_fp);

END $$	 

DELIMITER ;

/*view para trazer os dados do pedido*/
CREATE VIEW vw_funcionarios_pedidos AS SELECT fp.id, fp.fk_funcionario, f.funcionario, f.empresa, fp.obs, fp.pagamento, (SELECT SUM(valor * quantidade) FROM pedidos WHERE fk_funcionario_pedido = fp.id) AS total, fp.dt_registro, DATE_FORMAT(fp.dt_registro, '%d/%m/%Y') AS data_registro, TIME(fp.dt_registro) AS hora_registro FROM funcionarios_pedidos AS fp
INNER JOIN vw_funcionarios AS f ON fp.fk_funcionario = f.id;

/*view para trazer os dados dos pratos colocados no pedido*/
CREATE VIEW vw_pedidos AS SELECT pe.id, pe.fk_funcionario_pedido, fp.funcionario, fp.empresa, pe.descricao_prato AS prato, pe.tipo_prato AS tipo, pe.valor, pe.quantidade, (pe.valor * pe.quantidade) AS total FROM pedidos AS pe
INNER JOIN vw_funcionarios_pedidos AS fp ON pe.fk_funcionario_pedido = fp.id;

CREATE TABLE controle_quantidade(
	id INTEGER NOT NULL AUTO_INCREMENT,
	tipo_prato VARCHAR(30) NOT NULL,
	quantidade INTEGER NOT NULL,
	PRIMARY KEY(id)
);

INSERT INTO controle_quantidade(tipo_prato, quantidade)
	VALUES('Prato Principal', 1),('Prato Fitness', 1),('Acompanhamento', 3);
