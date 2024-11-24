DROP DATABASE IF EXISTS wx3;
CREATE DATABASE wx3;
use wx3;


CREATE TABLE IF NOT EXISTS endereco(
    id INT AUTO_INCREMENT PRIMARY KEY,
    logradouro VARCHAR(60) NOT NULL,
    cidade VARCHAR(30) NOT NULL,
    bairro VARCHAR(30) NOT NULL,
    numero INT,
    cep CHAR(8) NOT NULL,
    complemento VARCHAR(255)
)ENGINE=INNODB;
INSERT INTO endereco ( logradouro, cidade, bairro, numero, cep, complemento ) VALUES ( "Rua dr. Nagib Jorge Farah", "Cantagalo", "Centro", 62, '28500000', "Rua atrás do asilo" );


CREATE TABLE IF NOT EXISTS cliente(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomeCompleto VARCHAR(60) NOT NULL,
    cpf CHAR(11) NOT NULL,
    dataNascimento DATE NOT NULL,
    senha VARCHAR(255) NOT NULL,

    CONSTRAINT unq_cliente_cpf UNIQUE ( cpf )
)ENGINE=INNODB;
INSERT INTO cliente ( nomeCompleto, cpf, dataNascimento) VALUES ( "Igor Vieira Nara", "14954793742", "2006-02-13" );


CREATE TABLE IF NOT EXISTS categoria(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(30) NOT NULL, 
    descricao VARCHAR(255) NOT NULL,

    CONSTRAINT unq_categoria_nome UNIQUE ( nome )
)ENGINE=INNODB;
INSERT INTO categoria ( nome, descricao ) VALUES ( "Esportiva", "Teste" );


CREATE TABLE IF NOT EXISTS produto(
    id INT AUTO_INCREMENT PRIMARY KEY,
    idCategoria INT NOT NULL,
    nome VARCHAR(30) NOT NULL,
    arrayCores VARCHAR(255) NOT NULL,
    arrayUrlImg VARCHAR(255) NOT NULL,
    preco DECIMAL(9,2) NOT NULL,
    descricao VARCHAR(255) NOT NULL,
    dataCadastro DATE NOT NULL,
    peso DECIMAL(9,2) NOT NULL,

    CONSTRAINT fk_produto___categoria_id FOREIGN KEY ( idCategoria ) REFERENCES categoria ( id ) ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=INNODB;
INSERT INTO produto ( idCategoria, nome, arrayCores, arrayUrlImg, preco, descricao, dataCadastro, peso ) VALUES ( 1, "Blusa", "[vermelho, verde, preto]", "[url1.png, url2.png]", 49.99, "Blusa", "2024-11-19", 2.5 );


CREATE TABLE IF NOT EXISTS tamanho(
    id INT AUTO_INCREMENT PRIMARY KEY,
    sigla ENUM( "PP", "P", "M", "G", "GG", "XG" ) NOT NULL,

    CONSTRAINT unq_tamanho_sigla UNIQUE ( sigla )
)ENGINE=INNODB;
INSERT INTO tamanho ( sigla ) VALUES ( "PP" ), ( "P" ), ( "M" ), ( "G" ), ( "GG" ), ( "XG" );


CREATE TABLE IF NOT EXISTS tamanho_produto(
    idProduto INT NOT NULL,
    idTamanho INT NOT NULL,
    qtd INT NOT NULL,

    PRIMARY KEY ( idProduto, idTamanho ),
    CONSTRAINT fk_tamanho_produto__produto_id FOREIGN KEY ( idProduto ) REFERENCES produto ( id ) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_tamanho_produto__tamanho_id FOREIGN KEY ( idTamanho ) REFERENCES tamanho ( id ) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=INNODB;
INSERT INTO tamanho_produto ( idProduto, idTamanho, qtd ) VALUES ( 1, 1, 5 ), ( 1, 2, 10 ), ( 1, 3, 3 );


CREATE TABLE IF NOT EXISTS venda(
    id INT AUTO_INCREMENT PRIMARY KEY,
    idCliente INT NOT NULL,
    idEndereco INT NOT NULL,
    valorTotal DECIMAL(9,2) NOT NULL,
    valorFrete DECIMAL(9,2) NOT NULL,
    percentualDesconto INT NOT NULL,
    formaPagamento ENUM( "Pix", "Boleto", "Cartao" ) NOT NULL,

    CONSTRAINT fk_venda__cliente_id FOREIGN KEY ( idCliente ) REFERENCES cliente ( id ),
    CONSTRAINT fk_venda__endereco_id FOREIGN KEY ( idEndereco ) REFERENCES endereco ( id )
)ENGINE=INNODB;
INSERT INTO venda ( idCliente, idEndereco, valorTotal, valorFrete, percentualDesconto, formaPagamento ) VALUES ( 1, 1, 59.99, 10.00, 10, "Pix" );


CREATE TABLE IF NOT EXISTS venda_produto_tamanho(
    idVenda INT NOT NULL,
    idProduto INT NOT NULL,
    idTamanho INT NOT NULL,
    qtd INT NOT NULL,
    precoVenda DECIMAL(9,2) NOT NULL,

    PRIMARY KEY ( idVenda, idProduto, idTamanho ),
    CONSTRAINT fk_venda_produto_tamanho__venda_id FOREIGN KEY ( idVenda ) REFERENCES venda ( id ) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_venda_produto_tamanho__produto_id FOREIGN KEY ( idProduto ) REFERENCES produto ( id ) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_venda_produto_tamanho__tamanho_id FOREIGN KEY ( idTamanho ) REFERENCES tamanho ( id ) ON DELETE RESTRICT ON UPDATE CASCADE
)ENGINE=INNODB;
INSERT INTO venda_produto_tamanho ( idVenda, idProduto, idTamanho, qtd, precoVenda ) VALUES ( 1, 1, 1, 1, 49.99 );