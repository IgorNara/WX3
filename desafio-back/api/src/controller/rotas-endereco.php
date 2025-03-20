<?php
declare(strict_types = 1);

$gestor = new GestorEndereco( $conexao );

return [
    "/endereco" => [
        "GET" => function () use ( $gestor ) {
            try {
                $enderecos = $gestor->enderecos();
                respostaJson( false, "Endereços listados com sucesso!", 200, $enderecos );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "POST" => function ( $dados ) use ( $gestor, $persistivel ) {
            try {
                $idGerado = $gestor->cadastrar( $dados );
                respostaJson( false, "Endereço inserido com sucesso! O id gerado foi {$idGerado}", 201 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "PUT" => function ( $dados ) use ( $gestor ) {
            try {
                $gestor->alterar( $dados );
                respostaJson( false, "Endereço atualizado com sucesso!", 200 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/endereco/:id" => [
        "GET" => function ( $parametros ) use ( $gestor ) {
            try {
                $endereco = $gestor->enderecoComId( (int) $parametros[0] );
                respostaJson( false, "Endereço listado com sucesso!", 200, $endereco );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },  

        "DELETE" => function ( $parametros ) use ( $gestor ) {
            try {
                $gestor->removerComId( (int) $parametros[0] );
                respostaJson( false, "Endereço excluído com sucesso!", 204 );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ]
]
?>