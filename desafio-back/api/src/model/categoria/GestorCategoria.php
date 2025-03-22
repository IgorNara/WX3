<?php

declare(strict_types=1);

class GestorCategoria {
    private CategoriaPersistivel $persistivel;
    private Controller $controller;

    public function __construct( PDO $conexao ){
        $this->persistivel = new CategoriaPersistivelEmBDR( $conexao );
        $this->controller = new Controller( $this->persistivel );
    }


    public function categorias(): array {
        return $this->controller->get();
    }


    public function categoriaComId( int $id ): Categoria {
        return $this->controller->get( $id, "Erro ao buscar Categoria" );
    }


    public function cadastrar( array $dados ): int {
        $categoria = new Categoria( 0, $dados["nome"] ?? "", $dados["descricao"] ?? "" );
        return $this->controller->post( $categoria, "Erro ao cadastrar Categoria" );
    }


    public function alterar( array $dados ): void {
        $categoria = new Categoria( $dados["id"] ?? 0, $dados["nome"] ?? "", $dados["descricao"] ?? "" );
        if( ! $this->controller->put( $categoria, "Erro ao alterar categoria" ) ) {
            throw new RuntimeException( "Erro ao alterar Categoria - Registro não encontrado", 400 );
        }   
    }


    public function removerComId( int $id ): void {
        $this->controller->delete( $id, "Erro ao remover Categoria" );
    }

} 

?>