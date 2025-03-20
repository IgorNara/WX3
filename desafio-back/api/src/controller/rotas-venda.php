<?php
declare(strict_types = 1);

$gestor = new GestorVendaProdutoTamanho( $conexao );

return [
    "/venda" => [
        "GET" => function () use ( $gestor ) {
            try {
                $vendas = $gestor->vendas();
                respostaJson( false, "Vendas listadas com sucesso!", 200, $vendas );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ],

    "/venda/:id" => [
        "GET" => function ( $parametros ) use ( $gestor ) {
            try { 
                $venda = $gestor->vendaComId( (int) $parametros[0] );
                respostaJson( false, "Venda listada com sucesso!", 200, $venda );
            } catch ( RuntimeException $erro ) {
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ]
]
?>