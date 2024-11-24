<?php
declare(strict_types = 1);


$tamanhoPersistivel = new TamanhoPersistivelEmBDR( $conexao );


return [
    "/tamanho" => [
        "GET" => function () use ( $tamanhoPersistivel ) {
            $tamanhos = $tamanhoPersistivel->obterTodos();
            respostaJson( false, "Listagem efetuada com sucesso!", 200, $tamanhos );
        }
    ],

    "/tamanho/:id" => [
        "GET" => function ( $parametros ) use ( $tamanhoPersistivel ) {
            if( ! $tamanhoPersistivel->existeComId( (int) $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );

            respostaJson( false, "Informações listadas com sucesso!", 200, $tamanhoPersistivel->obterPeloId( (int) $parametros[0] ) );
        }
    ]
]
?>