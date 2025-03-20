<?php

declare(strict_types=1);

class GestorProduto {
    private ProdutoPersistivel $persistivel;
    private Controller $controller;

    public function __construct( PDO $conexao ){
        $this->persistivel = new ProdutoPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function produtos(): array {
        return $this->controller->get();
    }


    public function produtoComId( $id ): Produto {
        return $this->controller->get( $id );
    }


    public function cadastrar( array $dados, GestorTamanhoProduto $gestorTamanhoProduto ): int {
        $categoria = new Categoria( $dados["idCategoria"] );            
        $produto = new Produto( 0, $categoria, $dados["nome"], $dados["cores"], $dados["imagens"], $dados["preco"], $dados["descricao"], $dados["dataCadastro"], $dados["peso"] );            
        $idGeradoProduto = $this->controller->post( $produto );
        foreach( $dados["tamanhos"] as $dadosTamanho ) {
            $dadosTamanho["idProduto"] = $idGeradoProduto;
            $gestorTamanhoProduto->cadastrar( $dadosTamanho );
        }
        return $idGeradoProduto;
    }


    public function alterar( array $dados ): void {
        $categoria = new Categoria( $dados["idCategoria"] );
        $produto = new Produto( $dados["id"], $categoria, $dados["nome"], $dados["cores"], $dados["imagens"], $dados["preco"], $dados["descricao"], $dados["dataCadastro"], $dados["peso"] );
        if( ! $this->controller->put( $produto ) ) {
            throw new RuntimeException( "Produto não encontrado para atualização.", 400 );
        }   
    }


    public function removerComId( int $id ): void {
        $this->controller->delete( $id );    
    }


    public function rank(): array {
        return $this->persistivel->rankProdutosMaisVendidos();
    }
} 

?>