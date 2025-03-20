<?php
declare(strict_types = 1);

$gestor = new GestorCategoria( $conexao );

return [
    "/categoria" => [
        "GET" => function () use ( $gestor ) { 
            try {
                $categorias = $gestor->categorias();
                respostaJson( false, "Categorias listadas com sucesso!", 200, $categorias );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "POST" => function ( $dados ) use ( $gestor ) { 
            try {
                $idGerado = $gestor->cadastrar( $dados );
                respostaJson( false, "Categoria inserida com sucesso! O id gerado foi {$idGerado}", 201 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        },

        "PUT" => function ( $dados ) use ( $gestor ) {
            try {
                $gestor->alterar( $dados );
                respostaJson( false, "Categoria atualizada com sucesso!", 200 );
            } catch ( EntradaInvalidaException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/categoria/:id" => [
        "GET" => function ( $parametros ) use ( $gestor ) { 
            try {
                $categoria = $gestor->categoriaComId( (int) $parametros[0] );
                respostaJson( false, "Categoria listada com sucesso!", 200, $categoria );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            } 
        },

        "DELETE" => function ( $parametros ) use ( $gestor ) { 
            try {
                $gestor->removerComId( (int) $parametros[0] ); 
                respostaJson( false, "Categoria excluída com sucesso!", 204 );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ]
]
?>