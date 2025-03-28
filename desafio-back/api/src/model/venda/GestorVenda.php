<?php

declare(strict_types=1);

class GestorVenda {
    private VendaPersistivel $persistivel;
    private Controller $controller;

    public function __construct( PDO $conexao ){
        $this->persistivel = new VendaPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function cadastrar( array $dados, GestorProduto $gestorProduto ): int {
        $cliente = new Cliente( $dados["idCliente"] ?? 0 );
        $endereco = new Endereco( $dados["endereco"]["id"] ?? 0 );
        $venda = new Venda( 0, $cliente, $endereco, FormaPagamento::from( $dados["formaPagamento"] ) ?? null );
        $venda->setPercentualDesconto();
        $venda->calcularValorTotal( $dados["produtos"] ?? [], $gestorProduto );
        return $this->controller->post( $venda );
    }

} 

?>