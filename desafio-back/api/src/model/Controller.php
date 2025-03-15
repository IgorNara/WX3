<?php

declare( strict_types = 1 );

class Controller {

    public function __construct( 
        private object $persistivel 
    ) {}


    public function get( ?int $id = null ): void {     
        if( ! $id ) 
            respostaJson( false, "Informações listadas com sucesso!", 200, $this->persistivel->obterTodos() );
        else if( $this->persistivel->existeComId( $id ) ) 
            respostaJson( false, "Informações listadas com sucesso!", 200, $this->persistivel->obterPeloId( $id ) );
        respostaJson( true, "Informações não encontradas!", 400 );
    }


    public function post( object $object ): void {
        $object->validar();
        $problemas = $object->getProblemas();
        if( ! empty( $problemas ) )
            respostaJson( false, "Erro ao efetuar cadastro - DADOS INVÁLIDOS", 500, $problemas );
        $idGerado = $this->persistivel->inserir( $object );
        respostaJson( false, "Cadastro efetuado com sucesso! O id gerado foi $idGerado!", 201 );
    } 


    public function postReturn( object $object ): array {
        $object->validar();
        $problemas = $object->getProblemas();
        if( ! empty( $problemas ) ) 
            return [
                "erro" => true,
                "msg" => "Erro ao efetuar cadastro - DADOS INVÁLIDOS",
                "problemas" => $problemas
            ];
        return [
            "erro" => false,
            "msg" => "Cadastro efetuado com sucesso!",
            "idGerado" => $this->persistivel->inserir( $object )
        ];
    } 


    public function put( object $object ): void {
        $object->validar();
        $problemas = $object->getProblemas();
        if( ! empty( $problemas ) )
            respostaJson( false, "Erro ao efetuar alteração - DADOS INVÁLIDOS", 500, $problemas );
        $this->persistivel->alterar( $object );
        respostaJson( false, "Alteração efetuada com sucesso!", 200 );
    }


    public function delete( int $id ): void {
        if( ! $this->persistivel->existeComId( $id ) )
            respostaJson( true, "Informações não encontradas!", 400 );
        $this->persistivel->excluirPeloId( $id );
        respostaJson( false, "Exclusão efetuada com sucesso!", 204 );
    }
}

?>