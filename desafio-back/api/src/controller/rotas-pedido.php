<?php
declare(strict_types = 1);

$gestorVPT = new GestorVendaProdutoTamanho( $conexao );
$gestorEndereco = new GestorEndereco( $conexao );
$gestorClienteEndereco = new GestorClienteEndereco( $conexao );
$gestorVenda = new GestorVenda( $conexao );
$gestorProduto = new GestorProduto( $conexao );
$gestorTamanhoProduto = new GestorTamanhoProduto( $conexao );

$gestorTransacao = new GestorTransacao( $conexao );

return [
    "/pedido" => [
        "POST" => function ( $dados ) use ( $gestorTransacao, $gestorVPT, $gestorEndereco, $gestorClienteEndereco, $gestorVenda, $gestorProduto, $gestorTamanhoProduto ) {            
            try {
                $gestorTransacao->iniciar();
                $idGeradoVenda = $gestorVPT->cadastrar( $dados, $gestorEndereco, $gestorClienteEndereco, $gestorTamanhoProduto, $gestorVenda, $gestorProduto );
                $gestorTransacao->confirmar();
                respostaJson( false, "Pedido efetuado com sucesso! Nº do pedido: {$idGeradoVenda}", 201 );
            } catch ( EntradaInvalidaException $erro ) {
                $gestorTransacao->reverter();
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro->getProblems() );
            } catch ( RuntimeException $erro ) {
                $gestorTransacao->reverter();
                respostaJson( true, $erro->getMessage(), $erro->getCode(), $erro );
            }
        }
    ]
]
?>