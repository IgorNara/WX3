<?php

declare(strict_types=1);

class GestorEndereco {
    private EnderecoPersistivel $persistivel;
    private Controller $controller;

    public function __construct( $conexao ){
        $this->persistivel = new EnderecoPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function enderecos(): array {
        return $this->controller->get();
    }


    public function enderecoComId( int $id ): Endereco {
        return $this->controller->get( $id );
    }


    public function cadastrar( array $dados ): int {
        $endereco = new Endereco( 0, $dados["logradouro"], $dados["cidade"], $dados["bairro"], $dados["cep"], $dados["numero"], $dados["complemento"] );
        return $this->controller->post( $endereco );
    }


    public function alterar( array $dados ): void {
        $endereco = new Endereco( $dados["id"] ?? 0, $dados["logradouro"], $dados["cidade"], $dados["bairro"], $dados["cep"], $dados["numero"], $dados["complemento"] );
        if( ! $this->controller->put( $endereco ) ) {
            throw new RuntimeException( "Endereço não encontrado para atualização.", 400 );
        }   
            
    }


    public function removerComId( int $id ): void {
        $this->controller->delete( $id ); 
    }
} 

?>