<?php
declare(strict_types = 1);

$persistivel = new EnderecoPersistivelEmBDR( $conexao );
$controller = new Controller( $persistivel );

return [
    "/endereco" => [
        "GET" => function () use ( $controller ) {
            $controller->get();
        },

        "POST" => function ( $dados ) use ( $controller ) {
            $controller->post( new Endereco( 0, $dados["logradouro"], $dados["cidade"], $dados["bairro"], $dados["cep"], $dados["numero"], $dados["complemento"] , ) );
        },

        "PUT" => function ( $dados ) use ( $controller ) {
            $controller->put( new Endereco( $dados["id"], $dados["logradouro"], $dados["cidade"], $dados["bairro"], $dados["cep"], $dados["numero"], $dados["complemento"] ) );
        }
    ],

    "/endereco/:id" => [
        "GET" => function ( $parametros ) use ( $controller ) {
            $controller->get( (int) $parametros[0] );
        },  

        "DELETE" => function ( $parametros ) use ( $controller ) {
            $controller->delete( (int) $parametros[0] );
        }
    ]
]
?>