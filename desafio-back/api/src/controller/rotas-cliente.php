<?php
declare(strict_types = 1);

$persistivel = new ClientePersistivelEmBDR( $conexao );
$controller = new Controller( $persistivel );

return [
    "/cliente/logar" => [
        "POST" => function ( $dados ) use ( $persistivel ) {
            $cliente = new Cliente( 0, "", $dados["cpf"], "", $dados["senha"] );
            $persistivel->logar( $cliente );
            respostaJson( false, "Login efetuado com sucesso!", 200 );
        }
    ],

    "/cliente" => [
        "GET" => function () use ( $controller ) {
            $controller->get();
        },

        "POST" => function ( $dados ) use ( $controller ) {
            $controller->post( new Cliente( 0, $dados["nomeCompleto"], $dados["cpf"], $dados["dataNascimento"], $dados["senha"] ) );
        },

        "PUT" => function ( $dados ) use ( $controller ) {
            $controller->put( new Cliente( $dados["id"], $dados["nomeCompleto"], $dados["cpf"], $dados["dataNascimento"] ) );
        }
    ],

    "/cliente/:id" => [
        "GET" => function ( $parametros ) use ( $controller ) {
            $controller->get( (int) $parametros[0] );
        },

        "DELETE" => function ( $parametros ) use ( $controller ) {
            $controller->delete( (int) $parametros[0] );
        }
    ]
]
?>