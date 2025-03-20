<?php
declare(strict_types = 1);

// Gestores
$gestorProduto = new GestorProduto( $conexao );
$gestorTamanhoProduto = new GestorTamanhoProduto( $conexao );
$gestorTransacao = new GestorTransacao( $conexao );

return [
    "/produto" => [
        "GET" => function () use ( $gestorProduto ) {
            try {
                $produtos = $gestorProduto->produtos();
                respostaJson( false, "Produtos listados com sucesso!", 200, $produtos );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "POST" => function ( $dados ) use ( $gestorProduto, $gestorTamanhoProduto, $gestorTransacao ) {
            try {
                $gestorTransacao->iniciar();
                $idGerado = $gestorProduto->cadastrar( $dados, $gestorTamanhoProduto );
                $gestorTransacao->confirmar();
                respostaJson( false, "Produto inserido com sucesso! O id gerado foi {$idGerado}", 201 );
            } catch ( EntradaInvalidaException $erro ) {
                $gestorTransacao->reverter();
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                $gestorTransacao->reverter();
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "PUT" => function ( $dados ) use ( $gestorProduto ) {
            try {
                $gestorProduto->alterar( $dados );
                respostaJson( false, "Produto atualizado com sucesso!", 200 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/produto/:id" => [
        /** Retorna todos os tamanhos de um produto */
        "GET" => function ( $parametros ) use ( $gestorTamanhoProduto ) { 
            try {
                $tamanhosProduto = $gestorTamanhoProduto->tamanhosProdutoComIdProduto( (int) $parametros[0] );   
                respostaJson( false, "Tamanhos do produto listados com sucesso!", 200, $tamanhosProduto );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        /** Deleta o produto e, consequentemente, todas as relações entre ele seus tamanhos */
        "DELETE" => function ( $parametros ) use ( $gestorProduto ) {
            try {
                $gestorProduto->removerComId( (int) $parametros[0] );
                respostaJson( false, "Produto excluído com sucesso!", 204 );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/produto/rank" => [
        "GET" => function () use ( $gestorProduto ) {
            $produtos = $gestorProduto->rank();
            respostaJson( false, "Rank dos produtos listado com sucesso!", 200, $produtos );
        }
    ]
]
?>