<?php

declare( strict_types = 1 );

class Controller {

    public function __construct( 
        private object $persistivel 
    ) {}


    public function get( ?int $id = null ): array|object {     
        if( ! $id ) {
            return $this->persistivel->obterTodos();
        }
        else if( $this->persistivel->existeComId( $id ) ) {
            return $this->persistivel->obterPeloId( $id );
        }
        throw new RuntimeException( "Informações não encontradas.", 400 );
    }


    public function post( Validavel $object ): int {
        $object->validar();
        $problemas = $object->getProblemas();
        if( ! empty( $problemas ) ) 
            throw new EntradaInvalidaException( "Erro ao cadastrar informações - Dados Inválidos", 500, $problemas );
        return $this->persistivel->inserir( $object );
    } 


    public function put( Validavel $object ): bool {
        $object->validar();
        $problemas = $object->getProblemas();
        if( ! empty( $problemas ) ) 
            throw new EntradaInvalidaException( "Erro ao alterar informações - Dados inválidos", 500, $problemas );
        return $this->persistivel->alterar( $object );
    }


    public function delete( int $id ): void {
        if( ! $this->persistivel->excluirPeloId( $id ) )
            throw new RuntimeException( "Categoria não encontrada.", 400 );
    }
}

?>