<?php

declare(strict_types=1);

class GestorCategoria {
    private CategoriaPersistivel $persistivel;
    private Controller $controller;

    public function __construct( $conexao ){
        $this->persistivel = new CategoriaPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function categorias(): array {
        return $this->controller->get();
    }


    public function categoriaComId( int $id ): Categoria {
        $categoria = $this->controller->get( $id );
        if( $categoria === null )
            throw new RuntimeException( "Categoria não encontrada.", 400 );
        return $categoria;
    }


    public function cadastrar( array $dados ): int {
        $categoria = new Categoria( 0, $dados["nome"] ?? "", $dados["descricao"] ?? "" );
        return $this->controller->post( $categoria );
    }


    public function alterar( array $dados ): void {
        $categoria = new Categoria( $dados["id"] ?? 0, $dados["nome"] ?? "", $dados["descricao"] ?? "" );
        if( ! $this->controller->put( $categoria ) ) {
            throw new RuntimeException( "Categoria não encontrada para atualização.", 400 );
        }   
    }


    public function removerComId( int $id ): void {
        $this->controller->delete( $id );
    }

} 

?>