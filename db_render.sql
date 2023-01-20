CREATE SCHEMA db_renderer;
USE db_renderer;
CREATE TABLE clientes (
				idCliente INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idCliente),
                nome VARCHAR(30),
                cpf VARCHAR(16),
                telefone VARCHAR(16),
                email VARCHAR(30),
                obs VARCHAR(200),
                statuscli INT(1));
INSERT INTO clientes (nome, cpf, telefone, email, obs, statuscli)
			VALUES	('Client 1','111.111.111-11','(99) 9 9999-9999','client@testmail.com','Teste',1);
CREATE TABLE usuarios (
				idUsuario INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idUsuario),
                nome VARCHAR(30),
                telefone VARCHAR(16),
                email VARCHAR(30),
                statuscli INT(1),
				senha VARCHAR(200));
INSERT INTO usuarios (nome, telefone, email, statuscli, senha)
			VALUES	('admin','(99) 9 9999-9999','admin@testmail.com',2,'202cb962ac59075b964b07152d234b70');
INSERT INTO usuarios (nome, telefone, email, statuscli, senha)
			VALUES	('empregado','(99) 9 9999-9999','empregado@testmail.com',1,'202cb962ac59075b964b07152d234b70');
CREATE TABLE produtos (
				idProduto INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idProduto),
                descricao VARCHAR(30),
                valor VARCHAR(12),
                obs VARCHAR(30),
                codbar VARCHAR(30),
                quantidade FLOAT);
CREATE TABLE pedidos (
				idPedido INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idPedido),
                idCliente INT(11),
                FOREIGN KEY (idCliente) REFERENCES clientes(idCliente),
                idUsuario INT(11),
                FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario),
                quantidade FLOAT,
                nf VARCHAR(30),
                statusped INT(1),
                preco VARCHAR(15));
CREATE TABLE agendamentos (
				idAgendamento INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idAgendamento),
                idUsuario INT(11),
                FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario),
                idCliente INT(11),
                FOREIGN KEY (idCliente) REFERENCES clientes(idCliente),
                idPedido INT(11),
                FOREIGN KEY (idPedido) REFERENCES pedidos(idPedido),
                hora_ini DATETIME,
                hora_fim DATETIME,
                data_agend DATETIME,
                descricao VARCHAR(200));
CREATE TABLE avaliacoes (
				idAvaliacoes INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idAvaliacoes),
                idCliente INT(11),
                FOREIGN KEY (idCliente) REFERENCES clientes(idCliente),
                idUsuario INT(11),
                FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario),
                data_hora DATETIME,
                descricao VARCHAR(100),
                stats INT(3));
CREATE TABLE end_estoque (
				idend_estoque INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idend_estoque),
                endereco VARCHAR(10));
CREATE TABLE itens_estoque (
				iditens_estoque INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (iditens_estoque),
                idProduto INT(11),
                FOREIGN KEY (idProduto) REFERENCES produtos(idProduto),
                idend_estoque INT(11),
                quantidade FLOAT);
CREATE TABLE itens_pedido (
				idItens_pedido INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idItens_pedido),
                idPedido INT(11),
                FOREIGN KEY (idPedido) REFERENCES pedidos(idPedido),
                quantidade FLOAT,
                idProduto INT(11),
                FOREIGN KEY (idProduto) REFERENCES produtos(idProduto),
                preco VARCHAR(15));
CREATE TABLE list_perg_ava (
				idLPA INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idLPA),
                idPergunta INT(11),
                idAvaliacoes INT(11));   
CREATE TABLE nf (
				idNF INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idNF),
                idPedido INT(11),
                numero VARCHAR(20),
                serie INT(3),
                chave VARCHAR(45),
                data_hora DATETIME); 
CREATE TABLE perguntas (
				idPergunta INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idPergunta),
                pergunta VARCHAR(200));
INSERT INTO perguntas (pergunta) VALUES ('Qual a sua idade?');                
CREATE TABLE resp_ava (
				idResp_ava INT(11) NOT NULL AUTO_INCREMENT,
                PRIMARY KEY (idResp_ava),
                idLPA INT(11),
                idAvaliacoes INT(11),
                resposta VARCHAR(200));                


