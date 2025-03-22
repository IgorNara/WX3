<?php

declare(strict_types=1);

class GestorTamanhoProduto {
    private TamanhoProdutoPersistivel $persistivel;
    private Controller $controller;

    public function __construct( PDO $conexao ){
        $this->persistivel = new TamanhoProdutoPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function tamanhoProdutoComId( int $idProduto, int $idTamanho ): TamanhoProduto {
        return $this->persistivel->obterPeloIdProdutoTamanho( $idProduto, $idTamanho );   
    }


    public function tamanhosProdutoComIdProduto( int $idProduto ): array {
        return $this->persistivel->obterPeloIdProduto( $idProduto );       
    }


    public function cadastrar( array $dados ): void {
        $tamanho = new Tamanho( $dados["id"] );
        $produto = new Produto( $dados["idProduto"] );
        $tamanhoProduto = new TamanhoProduto( $produto, $tamanho, $dados["qtd"] );
        $this->controller->post( $tamanhoProduto, "Erro ao relacionar o Produto com o Tamanho" );
    }


    public function alterar( array $dados ): void {
        $tamanho = new Tamanho( $dados["tamanho"]->id );
        $produto = new Produto( $dados["produto"]->id );
        $tamanhoProduto = new TamanhoProduto( $produto, $tamanho, $dados["qtd"] );
        if( ! $this->controller->put( $tamanhoProduto, "Erro ao alterar a relação entre o Tamanho e o Produto" ) )
            throw new RuntimeException( "Erro ao alterar a relação entre o Tamanho e o Produto - Registro não encontrado", 400 );
    }
} 

?>