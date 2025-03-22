<?php

declare(strict_types=1);

class GestorClienteEndereco {
    private ClienteEnderecoPersistivel $persistivel;
    private Controller $controller;

    public function __construct( PDO $conexao ){
        $this->persistivel = new ClienteEnderecoPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function enderecosComIdCliente( int $idCliente ): array {
        return $this->controller->get( $idCliente, "Erro ao buscar Endereços do Cliente" );
    }


    public function cadastrar( array $dados, GestorEndereco $gestorEndereco ): int {
        $cliente = new Cliente( $dados["idCliente"] ); // $_SESSION["user_id"]
        $idGeradoEndereco = $gestorEndereco->cadastrar( $dados );
        $clienteEndereco = new ClienteEndereco( $cliente, new Endereco( $idGeradoEndereco ) );
        $this->controller->post( $clienteEndereco, "Erro ao relacionar o Cliente com o Endereço" );
        return $idGeradoEndereco;
    }
} 

?>