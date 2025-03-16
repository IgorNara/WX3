<?php
declare(strict_types = 1);

// Persistíveis
$vptPersistivel = new VendaProdutoTamanhoPersistivelEmBDR( $conexao );
$enderecoPersistivel = new EnderecoPersistivelEmBDR( $conexao );
$vendaPersistivel = new VendaPersistivelEmBDR( $conexao );
$produtoPersistivel = new ProdutoPersistivelEmBDR( $conexao );
$tamanhoProdutoPersistivel = new TamanhoProdutoPersistivelEmBDR( $conexao );

// Controllers
$controllerVPT = new Controller( $vptPersistivel );
$controllerEndereco = new Controller( $enderecoPersistivel );
$controllerVenda = new Controller( $vendaPersistivel );

$gestorTransacao = new GestorTransacao( $conexao );

return [
    "/pedido" => [
        "POST" => function ( $dados ) use ( $controllerVPT, $controllerEndereco, $controllerVenda, $produtoPersistivel, $tamanhoProdutoPersistivel, $gestorTransacao ) {            
            $gestorTransacao->iniciar();
            $dadosEndereco = $dados["endereco"];
            $endereco = new Endereco( $dadosEndereco["id"] );
            if( ! $endereco->id > 0 ) { // Insere um novo endereço
                $endereco = new Endereco( 0, $dadosEndereco["logradouro"], $dadosEndereco["cidade"], $dadosEndereco["bairro"], $dadosEndereco["cep"], $dadosEndereco["numero"] ?? null, $dadosEndereco["complemento"] ?? null );
                $respostaEndereco = $controllerEndereco->postReturn( $endereco );
                if( $respostaEndereco["erro"] ) {
                    $gestorTransacao->reverter();
                    respostaJson( true, $respostaEndereco["msg"], 500, $respostaEndereco["problemas"] );
                }
            }

            $dadosProdutos = $dados["produtos"];

            // Insere uma venda
            $cliente = new Cliente( $dados["idCliente"] );
            $venda = new Venda( 0, $cliente, $endereco, FormaPagamento::from( $dados["formaPagamento"] ) );
            $venda->valorTotal = $venda->calcularValorTotal( $dadosProdutos, $produtoPersistivel );
            $respostaVenda = $controllerVenda->postReturn( $venda );
            if( $respostaVenda["erro"] ) {
                $gestorTransacao->reverter();
                respostaJson( true, $respostaVenda["msg"], 500, $respostaVenda["problemas"] );
            }
            $venda->id = $respostaVenda["idGerado"];

            foreach( $dadosProdutos as $dadosProduto ) {  
                // Busca as informações do produto comprado  
                $produto = $produtoPersistivel->obterPeloId( $dadosProduto["id"] );

                // Cria uma relação entre venda, produto e tamanho para todos os tamanhos vendidos de cada produto
                $dadosTamanhos = $dadosProduto["tamanhos"];
                foreach( $dadosTamanhos as $dadosTamanho ) {
                    $tamanho = new Tamanho( $dadosTamanho["id"] );  
                    $precoVenda = ( $produto->preco * $dadosTamanho["qtd"] );

                    // Insere a relação
                    $vpt = new VendaProdutoTamanho( $venda, $produto, $tamanho, $dadosTamanho["qtd"], $precoVenda );
                    $respostaVPT = $controllerVPT->postReturn( $vpt );
                    if( $respostaVPT["erro"] ) {
                        $gestorTransacao->reverter();
                        respostaJson( true, $respostaVPT["msg"], 500, $respostaVPT["problemas"] );
                    }

                    // Altera o estoque desse tamanho do produto
                    $tamanhoProduto = $tamanhoProdutoPersistivel->obterPeloIdProdutoTamanho( (int) $produto->id, (int) $tamanho->id );
                    $tamanhoProduto->qtd -= $dadosTamanho["qtd"];
                    $tamanhoProduto->validar();

                    $problemasTamanhoProduto = $tamanhoProduto->getProblemas();
                    if( ! empty( $problemasTamanhoProduto ) ) {
                        $gestorTransacao->reverter();
                        respostaJson( true, "Erro ao efetuar pedido - DADOS INVÁLIDOS", 500, $problemasTamanhoProduto );
                    }

                    $tamanhoProdutoPersistivel->alterar( $tamanhoProduto );

                }    
            }
            $gestorTransacao->confirmar();
            respostaJson( false, "Pedido efetuado com sucesso! Nº do pedido: {$venda->id}", 201 );
        }
    ]
]
?>