<?php
declare(strict_types = 1);


$tamanhoPersistivel = new TamanhoPersistivelEmBDR( $conexao );


return [
    "/tamanho" => [
        "GET" => function () use ( $tamanhoPersistivel ) {
            $tamanhos = $tamanhoPersistivel->obterTodos();
            respostaJson( false, "Listagem efetuada com sucesso!", 200, $tamanhos );
        }
    ]
]
?>