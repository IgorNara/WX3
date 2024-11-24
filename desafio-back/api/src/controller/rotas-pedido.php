<?php
declare(strict_types = 1);

$vptPersistivel = new VendaProdutoTamanhoPersistivelEmBDR( $conexao );
$enderecoPersistivel = new EnderecoPersistivelEmBDR( $conexao );
$vendaPersistivel = new VendaPersistivelEmBDR( $conexao );
$produtoPersistivel = new ProdutoPersistivelEmBDR( $conexao );
$tamanhoProdutoPersistivel = new TamanhoProdutoPersistivelEmBDR( $conexao );
$gestorTransacao = new GestorTransacao( $conexao );

// formato esperado em $dados = {
//     idCliente,
//     formaPagamento,
//     produtos: {
//         { 
//             id: 0, // id do produto
//             tamanhos: {
//                 {
//                     id: 0, // id do tamanho
//                     qtd: 0 // quantidade que saiu desse tamanho
//                 }, 
//                 // mais tamanhos podem ser enviados seguinto esse formato
//             }
//         },
//         // mais produtos podem ser enviados seguindo esse formato
//     },
//     endereco: {
//         id: 0, // O id deve sempre ser fornecido. Caso seja novo, deve ser enviado como 0 seguido do restante das informações
//     }
// }

return [
    "/pedido" => [
        "POST" => function ( $dados ) use ( $vptPersistivel, $enderecoPersistivel, $vendaPersistivel, $produtoPersistivel, $tamanhoProdutoPersistivel, $gestorTransacao ) {            
            try {
                $gestorTransacao->iniciar();
                $dadosEndereco = $dados["endereco"];
                $endereco = new Endereco( $dadosEndereco["id"] );
                if( ! $endereco->id > 0 ) { // Insere um novo endereço
                    $endereco = new Endereco( 0, $dadosEndereco["logradouro"], $dadosEndereco["cidade"], $dadosEndereco["bairro"], $dadosEndereco["cep"], $dadosEndereco["numero"] ?? null, $dadosEndereco["complemento"] ?? null );
                    $endereco->validar();

                    // Verifica se o cliente forneceu algum valor inválido
                    $problemasEndereco = $endereco->getProblemas();
                    if( ! empty( $problemasEndereco ) )
                        respostaJson( true, "Erro ao cadastrar endereço - DADOS INVÁLIDOS", 500, $problemasEndereco );

                    $enderecoPersistivel->inserir( $endereco );
                }

                $dadosProdutos = $dados["produtos"];

                // Insere uma venda
                $cliente = new Cliente( $dados["idCliente"] );
                $venda = new Venda( 0, $cliente, $endereco, FormaPagamentoDesconto::from( $dados["formaPagamento"] ) );
                $venda->valorTotal = $venda->calcularValorTotal( $dadosProdutos, $produtoPersistivel );
                $venda->validar();

                // Verifica se o cliente forneceu algum valor inválido
                $problemasVenda = $venda->getProblemas();
                if( ! empty( $problemasVenda ) ) {
                    $gestorTransacao->reverter();
                    respostaJson( true, "Erro ao efetuar pedido - DADOS INVÁLIDOS", 500, $problemasVenda );
                }

                $venda->id = $vendaPersistivel->inserir( $venda );

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
                        $vpt->validar();

                        $problemasVpt = $vpt->getProblemas();
                        if( ! empty( $problemasVpt ) ) {
                            $gestorTransacao->reverter();
                            respostaJson( true, "Erro ao efetuar pedido - DADOS INVÁLIDOS", 500, $problemasVpt );
                        }

                        $vptPersistivel->inserir( $vpt );

                        // Altera o estoque desse tamanho do produto
                        $tamanhoProduto = $tamanhoProdutoPersistivel->obterPeloId( $produto->id, $tamanho->id );
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
                respostaJson( false, "Pedido efetuado com sucesso! Nº do pedido: {$venda->id}", 200 );
            }
            catch (RuntimeException $e) {
                respostaJson( true, $e->getMessage(), 400 );
            }
            catch (Exception $e) {
                respostaJson( true, $e->getMessage(), 500 );
            }
        }
    ]
]
?>