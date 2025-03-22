<?php

declare(strict_types=1);

class GestorCliente {
    private ClientePersistivel $persistivel;
    private Controller $controller;

    public function __construct( PDO $conexao ){
        $this->persistivel = new ClientePersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function clientes(): array {
        return $this->controller->get();
    }


    public function clienteComId( int $id ): Cliente {
        return $this->controller->get( $id, "Erro ao buscar Cliente" );
    }


    public function cadastrar( array $dados ): int {
        $cliente = new Cliente( 0, $dados["nomeCompleto"], $dados["cpf"], $dados["dataNascimento"], $dados["senha"] );
        return $this->controller->post( $cliente, "Erro ao cadastrar Cliente" );       
    }


    public function alterar( array $dados ): void {
        $cliente = new Cliente( $dados["id"] ?? 0, $dados["nomeCompleto"], $dados["cpf"], $dados["dataNascimento"], $dados["senha"] );
        if( ! $this->controller->put( $cliente, "Erro ao alterar Cliente" ) ) {
            throw new RuntimeException( "Erro ao alterar Cliente - Registro não encontrado", 400 );
        }   
    }


    public function removerComId( int $id ): void {
        $this->controller->delete( $id, "Erro ao remover Cliente" );
    }


    public function logar( array $dados ): void {
        $cliente = new Cliente( 0, "", $dados["cpf"], "", $dados["senha"] );
        $this->persistivel->logar( $cliente );
    }
} 

?>