<?php
declare(strict_types = 1);

$gestorCliente = new GestorCliente( $conexao );
$gestorClienteEndereco = new GestorClienteEndereco( $conexao );

return [
    "/cliente/logar" => [
        "POST" => function ( $dados ) use ( $gestorCliente ) {
            try {
                $gestorCliente->logar( $dados );
                respostaJson( false, "Login efetuado com sucesso!", 200 );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/cliente/enderecos/:id" => [
        "GET" => function ( $parametros ) use ( $gestorClienteEndereco ) {
            try {
                $enderecos = $gestorClienteEndereco->enderecosComIdCliente( (int) $parametros[0] );
                respostaJson( false, "Endereços do cliente listados com sucesso!", 200, $enderecos );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/cliente" => [
        "GET" => function () use ( $gestorCliente ) {
            try {
                $clientes = $gestorCliente->clientes();
                respostaJson( false, "Clientes listados com sucesso!", 200, $clientes );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "POST" => function ( $dados ) use ( $gestorCliente ) {
            try {
                $idGerado = $gestorCliente->cadastrar( $dados );
                respostaJson( false, "Cliente inserido com sucesso! O id gerado foi {$idGerado}", 201 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "PUT" => function ( $dados ) use ( $gestorCliente ) {
            try {
                $gestorCliente->alterar( $dados );
                respostaJson( false, "Cliente atualizado com sucesso!", 200 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/cliente/:id" => [
        "GET" => function ( $parametros ) use ( $gestorCliente ) {
            try {
                $cliente = $gestorCliente->clienteComId( (int) $parametros[0] );
                respostaJson( false, "Cliente listado com sucesso!", 200, $cliente );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "DELETE" => function ( $parametros ) use ( $gestorCliente ) {
            try {
                $gestorCliente->removerComId( (int) $parametros[0] );
                respostaJson( false, "Cliente excluído com sucesso!", 204 );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ]
]
?>