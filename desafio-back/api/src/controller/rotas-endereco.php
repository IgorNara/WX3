<?php
declare(strict_types = 1);

$gestorEndereco = new GestorEndereco( $conexao );
$gestorClienteEndereco = new GestorClienteEndereco( $conexao );
$gestorTransacao = new GestorTransacao( $conexao );

return [
    "/endereco" => [
        "GET" => function () use ( $gestorEndereco ) {
            try {
                $enderecos = $gestorEndereco->enderecos();
                respostaJson( false, "Endereços listados com sucesso!", 200, $enderecos );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "POST" => function ( $dados ) use ( $gestorClienteEndereco, $gestorEndereco, $gestorTransacao ) {
            try {
                $gestorTransacao->iniciar();
                $idGeradoEndereco = $gestorClienteEndereco->cadastrar( $dados, $gestorEndereco );
                $gestorTransacao->confirmar();
                respostaJson( false, "Endereço inserido com sucesso! O id gerado foi {$idGeradoEndereco}", 201 );
            } catch ( EntradaInvalidaException $erro ) {
                $gestorTransacao->reverter();
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                $gestorTransacao->reverter();
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "PUT" => function ( $dados ) use ( $gestorEndereco ) {
            try {
                $gestorEndereco->alterar( $dados );
                respostaJson( false, "Endereço atualizado com sucesso!", 200 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/endereco/:id" => [
        "GET" => function ( $parametros ) use ( $gestorEndereco ) {
            try {
                $endereco = $gestorEndereco->enderecoComId( (int) $parametros[0] );
                respostaJson( false, "Endereço listado com sucesso!", 200, $endereco );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },  

        "DELETE" => function ( $parametros ) use ( $gestorEndereco ) {
            try {
                $gestorEndereco->removerComId( (int) $parametros[0] );
                respostaJson( false, "Endereço excluído com sucesso!", 204 );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ]
]
?>