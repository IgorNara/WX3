<?php
declare(strict_types = 1);

$gestor = new GestorTamanho( $conexao );

return [
    "/tamanho" => [
        "GET" => function () use ( $gestor ) {
            try {
                $tamanhos = $gestor->tamanhos();
                respostaJson( false, "Tamanhos listados com sucesso!", 200, $tamanhos );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/tamanho/:id" => [
        "GET" => function ( $parametros ) use ( $gestor ) {
            try {
                $tamanho = $gestor->tamanhoComId( (int) $parametros[0] );
                respostaJson( false, "Tamanho listado com sucesso!", 200, $tamanho );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ]
]
?>