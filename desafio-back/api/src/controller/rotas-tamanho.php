<?php
declare(strict_types = 1);


$persistivel = new TamanhoPersistivelEmBDR( $conexao );
$controller = new Controller( $persistivel );

return [
    "/tamanho" => [
        "GET" => function () use ( $controller ) {
            $controller->get();
        }
    ],

    "/tamanho/:id" => [
        "GET" => function ( $parametros ) use ( $controller ) {
            $controller->get( (int) $parametros[0] );
        }
    ]
]
?>