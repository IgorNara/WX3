<?php
declare(strict_types = 1);

$gestor = new GestorCliente( $conexao );

return [
    "/cliente/logar" => [
        "POST" => function ( $dados ) use ( $gestor, $persistivel ) {
            try {
                $gestor->logar( $dados, $persistivel );
                respostaJson( false, "Login efetuado com sucesso!", 200 );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/cliente" => [
        "GET" => function () use ( $gestor ) {
            try {
                $clientes = $gestor->clientes();
                respostaJson( false, "Clientes listados com sucesso!", 200, $clientes );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "POST" => function ( $dados ) use ( $gestor ) {
            try {
                $idGerado = $gestor->cadastrar( $dados );
                respostaJson( false, "Cliente inserido com sucesso! O id gerado foi {$idGerado}", 201 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "PUT" => function ( $dados ) use ( $gestor ) {
            try {
                $gestor->alterar( $dados );
                respostaJson( false, "Cliente atualizado com sucesso!", 200 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/cliente/:id" => [
        "GET" => function ( $parametros ) use ( $gestor ) {
            try {
                $cliente = $gestor->clienteComId( (int) $parametros[0] );
                respostaJson( false, "Cliente listado com sucesso!", 200, $cliente );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "DELETE" => function ( $parametros ) use ( $gestor ) {
            try {
                $gestor->removerComId( (int) $parametros[0] );
                respostaJson( false, "Cliente excluído com sucesso!", 204 );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ]
]
?>