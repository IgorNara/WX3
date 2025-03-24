<?php

declare(strict_types=1);

class GestorVendaProdutoTamanho {
    private VendaProdutoTamanhoPersistivel $persistivel;
    private Controller $controller;

    public function __construct( PDO $conexao ){
        $this->persistivel = new VendaProdutoTamanhoPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function vendas(): array {
        return $this->controller->get();
    }


    public function vendaComId( int $id ): array {
        return $this->controller->get( $id, "Erro ao buscar informações da Venda" ); 
    }


    public function cadastrar( array $dados, GestorEndereco $gestorEndereco, GestorClienteEndereco $gestorClienteEndereco, GestorTamanhoProduto $gestorTamanhoProduto, GestorVenda $gestorVenda, GestorProduto $gestorProduto ): int {
        $dadosEndereco = $dados["endereco"];
        $endereco = new Endereco( $dadosEndereco["id"] ?? 0 );
        if( ! $endereco->id > 0 ) { // Insere um novo endereço
            $dadosEndereco["idCliente"] = $dados["idCliente"] ?? 0;
            $gestorClienteEndereco->cadastrar( $dadosEndereco, $gestorEndereco );
        }

        $dadosProdutos = $dados["produtos"];

        // Insere uma venda
        $idGeradoVenda = $gestorVenda->cadastrar( $dados, $gestorProduto );
        $venda = new Venda( $idGeradoVenda );

        foreach( $dadosProdutos as $dadosProduto ) {  
            // Busca as informações do produto comprado  
            $produto = $gestorProduto->produtoComId( $dadosProduto["id"] ?? 0 );

            // Cria uma relação entre venda, produto e tamanho para todos os tamanhos vendidos de cada produto
            $dadosTamanhos = $dadosProduto["tamanhos"];
            foreach( $dadosTamanhos as $dadosTamanho ) {
                $tamanho = new Tamanho( $dadosTamanho["id"] ?? 0 );  
                $precoVenda = ( $produto->preco * $dadosTamanho["qtd"] ?? 0 );

                // Insere a relação
                $vpt = new VendaProdutoTamanho( $venda, $produto, $tamanho, $dadosTamanho["qtd"] ?? 0, $precoVenda );
                $this->controller->post( $vpt );

                // Altera o estoque desse tamanho do produto
                $arrayTamanhoProduto = ($gestorTamanhoProduto->tamanhoProdutoComId( $produto->id, $tamanho->id ))->toArray();
                $arrayTamanhoProduto["qtd"] -= $dadosTamanho["qtd"];
                $gestorTamanhoProduto->alterar( $arrayTamanhoProduto );
            }    
        }
        return $venda->id;
    }

} 

?>