<?php

declare(strict_types=1);

class GestorTamanhoProduto {
    private TamanhoProdutoPersistivel $persistivel;
    private Controller $controller;

    public function __construct( $conexao ){
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
        $tamanhoProduto = new TamanhoProduto( new Produto( $dados["idProduto"] ), $tamanho, $dados["qtd"] );
        $this->controller->post( $tamanhoProduto );
    }


    public function alterar( array $dados ): void {
        
    }
} 

?>