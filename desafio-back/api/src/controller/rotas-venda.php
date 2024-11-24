<?php
declare(strict_types = 1);


$vptPersistivel = new VendaProdutoTamanhoPersistivelEmBDR( $conexao );


return [
    "/venda" => [
        "GET" => function () use ( $vptPersistivel ) {
            $vpts = $vptPersistivel->obterTodos();
            respostaJson( false, "Listagem efetuada com sucesso!", 200, $vpts );
        }
    ],

    "/venda/:id" => [
        "GET" => function ( $parametros ) use ( $vptPersistivel ) {
            if( ! $vptPersistivel->existeComIdVenda( (int) $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );

            respostaJson( false, "Informações listadas com sucesso!", 200, $vptPersistivel->obterPeloIdVenda( (int) $parametros[0] ) );
        }
    ]
]
?>