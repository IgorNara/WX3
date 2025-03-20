<?php

declare(strict_types=1);

class GestorVendaProdutoTamanho {
    private VendaProdutoTamanhoPersistivel $persistivel;
    private Controller $controller;

    public function __construct( $conexao ){
        $this->persistivel = new VendaProdutoTamanhoPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function vendas(): array {
        return $this->controller->get();
    }


    public function vendaComId( int $id ): Venda {
        return $this->controller->get( $id ); 
    }


    public function cadastrar( array $dados, GestorEndereco $gestorEndereco, GestorTamanhoProduto $gestorTamanhoProduto, GestorVenda $gestorVenda, GestorProduto $gestorProduto ): int {
        $dadosEndereco = $dados["endereco"];
        $endereco = new Endereco( $dadosEndereco["id"] );
        if( ! $endereco->id > 0 ) { // Insere um novo endereço
            $endereco = new Endereco( 0, $dadosEndereco["logradouro"], $dadosEndereco["cidade"], $dadosEndereco["bairro"], $dadosEndereco["cep"], $dadosEndereco["numero"] ?? null, $dadosEndereco["complemento"] ?? null );
            $dados["endereco"]["id"] = $gestorEndereco->cadastrar( $dadosEndereco );
            // Inserir relação entre cliente e novo endereço
        }

        $dadosProdutos = $dados["produtos"];

        // Insere uma venda
        $idGeradoVenda = $gestorVenda->cadastrar( $dados, $gestorProduto );
        $venda = new Venda( $idGeradoVenda );

        foreach( $dadosProdutos as $dadosProduto ) {  
            // Busca as informações do produto comprado  
            $produto = $gestorProduto->produtoComId( $dadosProduto["id"] );

            // Cria uma relação entre venda, produto e tamanho para todos os tamanhos vendidos de cada produto
            $dadosTamanhos = $dadosProduto["tamanhos"];
            foreach( $dadosTamanhos as $dadosTamanho ) {
                $tamanho = new Tamanho( $dadosTamanho["id"] );  
                $precoVenda = ( $produto->preco * $dadosTamanho["qtd"] );

                // Insere a relação
                $vpt = new VendaProdutoTamanho( $venda, $produto, $tamanho, $dadosTamanho["qtd"], $precoVenda );
                $this->controller->post( $vpt );

                // Altera o estoque desse tamanho do produto
                $tamanhoProduto = $gestorTamanhoProduto->tamanhoProdutoComId( (int) $produto->id, (int) $tamanho->id );
                $tamanhoProduto->qtd -= $dadosTamanho["qtd"];
                $gestorTamanhoProduto->alterar( $tamanhoProduto->toArray() );
            }    
        }
        return $venda->id;
    }

} 

?>