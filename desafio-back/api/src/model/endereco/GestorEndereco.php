<?php

declare(strict_types=1);

class GestorEndereco {
    private EnderecoPersistivel $persistivel;
    private Controller $controller;

    public function __construct( PDO $conexao ){
        $this->persistivel = new EnderecoPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function enderecos(): array {
        return $this->controller->get();
    }


    public function enderecoComId( int $id ): Endereco {
        return $this->controller->get( $id, "Erro ao buscar Endereço" );
    }


    public function cadastrar( array $dados ): int {
        $endereco = new Endereco( 0, $dados["logradouro"] ?? "", $dados["cidade"] ?? "", $dados["bairro"] ?? "", $dados["cep"] ?? "", $dados["numero"] ?? "", $dados["complemento"] ?? "" );
        return $this->controller->post( $endereco, "Erro ao cadastrar Endereço" );
    }


    public function alterar( array $dados ): void {
        $endereco = new Endereco( $dados["id"] ?? 0, $dados["logradouro"] ?? "", $dados["cidade"] ?? "", $dados["bairro"] ?? "", $dados["cep"] ?? "", $dados["numero"] ?? 0, $dados["complemento"] ?? "" );
        if( ! $this->controller->put( $endereco, "Erro ao alterar Endereço" ) ) {
            throw new RuntimeException( "Erro ao alterar Endereço - Registro não encontrado", 400 );
        }    
    }


    public function removerComId( int $id ): void {
        $this->controller->delete( $id, "Erro ao remover Endereço" ); 
    }
} 

?>