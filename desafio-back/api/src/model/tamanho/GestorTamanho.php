<?php

declare(strict_types=1);

class GestorTamanho {
    private TamanhoPersistivel $persistivel;
    private Controller $controller;

    public function __construct( PDO $conexao ){
        $this->persistivel = new TamanhoPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function tamanhos(): array {
        return $this->controller->get();       
    }


    public function tamanhoComId( int $id ): Tamanho {
        return $this->controller->get( $id, "Erro ao buscar Tamanho" );
    }
} 

?>