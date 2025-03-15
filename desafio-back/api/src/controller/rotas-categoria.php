<?php
declare(strict_types = 1);

$persistivel = new CategoriaPersistivelEmBDR( $conexao );
$controller = new Controller( $persistivel );

return [
    "/categoria" => [
        "GET" => function () use ( $controller ) { 
            $controller->get();
        },

        "POST" => function ( $dados ) use ( $controller ) { 
            $controller->post( new Categoria( 0, $dados["nome"], $dados["descricao"] ) ); 
        },

        "PUT" => function ( $dados ) use ( $controller ) {
            $controller->put( new Categoria( $dados["id"], $dados["nome"], $dados["descricao"] ) );
        }
    ],

    "/categoria/:id" => [
        "GET" => function ( $parametros ) use ( $controller ) { 
            $controller->get( (int) $parametros[0] ); 
        },

        "DELETE" => function ( $parametros ) use ( $controller ) { 
            $controller->delete( (int) $parametros[0] ); 
        }
    ]
]
?>