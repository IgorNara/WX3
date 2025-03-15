<?php
declare(strict_types = 1);


$persistivel = new VendaProdutoTamanhoPersistivelEmBDR( $conexao );
$controller = new Controller( $persistivel );


return [
    "/venda" => [
        "GET" => function () use ( $controller ) {
            $controller->get();
        }
    ],

    "/venda/:id" => [
        "GET" => function ( $parametros ) use ( $controller ) {
            $controller->get( (int) $parametros[0] );
        }
    ]
]
?>