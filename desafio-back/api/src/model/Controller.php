<?php

declare( strict_types = 1 );

class Controller {

    public function __construct( 
        private object $persistivel 
    ) {}


    public function get( ?int $id = null, ?string $msgErro = null ): array|object {     
        if( ! $id ) {
            return $this->persistivel->obterTodos();
        }
        else if( $this->persistivel->existeComId( $id ) ) {
            return $this->persistivel->obterPeloId( $id );
        }
        throw new RuntimeException( $msgErro ? $msgErro . " - Registro não encontrado" : "Erro ao buscar informações - Registro não encontrado.", 400 );
    }


    public function post( Validavel $object, ?string $msgErro = null ): int {
        $object->validar();
        $problemas = $object->getProblemas();
        if( ! empty( $problemas ) ) 
            throw new EntradaInvalidaException( $msgErro ? $msgErro . " - Dados Inválidos" : "Erro ao cadastrar informações - Dados Inválidos", 500, $problemas );
        return $this->persistivel->inserir( $object );
    } 


    public function put( Validavel $object, ?string $msgErro = null ): bool {
        $object->validar();
        $problemas = $object->getProblemas();
        if( ! empty( $problemas ) ) 
            throw new EntradaInvalidaException( $msgErro ? $msgErro . " - Dados Inválidos" :  "Erro ao alterar informações - Dados inválidos", 500, $problemas );
        return $this->persistivel->alterar( $object );
    }


    public function delete( int $id, ?string $msgErro = null ): void {
        if( ! $this->persistivel->excluirPeloId( $id ) )
            throw new RuntimeException( $msgErro ? $msgErro . " - Registro não encontrado" : "Registro não encontrado.", 400 );
    }
}

?>