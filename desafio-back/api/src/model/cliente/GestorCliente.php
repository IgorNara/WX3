<?php

declare(strict_types=1);

class GestorCliente {
    private ClientePersistivel $persistivel;
    private Controller $controller;

    public function __construct( $conexao ){
        $this->persistivel = new ClientePersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function clientes(): array {
        return $this->controller->get();
    }


    public function clienteComId( int $id ): Cliente {
        return $this->controller->get( $id );
    }


    public function cadastrar( array $dados ): int {
        $cliente = new Cliente( 0, $dados["nomeCompleto"], $dados["cpf"], $dados["dataNascimento"], $dados["senha"] );
        return $this->controller->post( $cliente );       
    }


    public function alterar( array $dados ): void {
        $cliente = new Cliente( $dados["id"] ?? 0, $dados["nomeCompleto"], $dados["cpf"], $dados["dataNascimento"], $dados["senha"] );
        if( ! $this->controller->put( $cliente ) ) {
            throw new RuntimeException( "Cliente não encontrado para atualização.", 400 );
        }   
    }


    public function removerComId( int $id ): void {
        $this->controller->delete( $id );
    }


    public function logar( array $dados ): void {
        $cliente = new Cliente( 0, "", $dados["cpf"], "", $dados["senha"] );
        $this->persistivel->logar( $cliente );
    }
} 

?>