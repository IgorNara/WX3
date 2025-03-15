<?php
declare(strict_types = 1);

// Persistíveis
$produtoPersistivel = new ProdutoPersistivelEmBDR( $conexao );
$tamanhoProdutoPersistivel = new TamanhoProdutoPersistivelEmBDR( $conexao );

// Controllers
$controllerProduto = new Controller( $produtoPersistivel );
$controllerTamanhoProduto = new Controller( $tamanhoProdutoPersistivel );

$gestorTransacao = new GestorTransacao( $conexao );

return [
    "/produto" => [
        "GET" => function () use ( $controllerTamanhoProduto, $gestorTransacao ) {
            $controllerTamanhoProduto->get();
        },

        "POST" => function ( $dados ) use ( $controllerProduto, $controllerTamanhoProduto, $gestorTransacao ) {
            $gestorTransacao->iniciar();
            $categoria = new Categoria( $dados["idCategoria"] );            

            $produto = new Produto( 0, $categoria, $dados["nome"], $dados["cores"], $dados["imagens"], $dados["preco"], $dados["descricao"], $dados["dataCadastro"], $dados["peso"] );            
            $respostaProduto = $controllerProduto->postReturn( $produto );
            if( $respostaProduto["erro"] ) {
                $gestorTransacao->reverter();
                respostaJson( true, $respostaProduto["msg"], 500, $respostaProduto["problemas"] );
            }

            $dadosTamanhos = $dados["tamanhos"];
            foreach( $dadosTamanhos as $dadosTamanho ) {
                $tamanho = new Tamanho( $dadosTamanho["id"] );
                $tamanhoProduto = new TamanhoProduto( new Produto( $respostaProduto["idGerado"] ), $tamanho, $dadosTamanho["qtd"] );
                $respostaTamanhoProduto = $controllerTamanhoProduto->postReturn( $tamanhoProduto );
                if( $respostaTamanhoProduto["erro"] ) {
                    $gestorTransacao->reverter();
                    respostaJson( true, $respostaTamanhoProduto["msg"], 500, $respostaTamanhoProduto["problemas"] );
                }
            }
            
            $gestorTransacao->confirmar();
            respostaJson( false, "Cadastro efetuado com sucesso! O id gerado foi {$respostaProduto['idGerado']}!", 201 );
        },

        "PUT" => function ( $dados ) use ( $controllerProduto ) {
            $categoria = new Categoria( $dados["idCategoria"] );
            $produto = new Produto( $dados["id"], $categoria, $dados["nome"], $dados["cores"], $dados["imagens"], $dados["preco"], $dados["descricao"], $dados["dataCadastro"], $dados["peso"] );
            $controllerProduto->put( $produto );
        }
    ],

    "/produto/:id" => [
        /** Retorna todos os tamanhos de um produto */
        "GET" => function ( $parametros ) use ( $controllerTamanhoProduto ) { 
            $controllerTamanhoProduto->get( (int) $parametros[0] );            
        },

        /** Deleta o produto e, consequentemente, todas as relações entre ele seus tamanhos */
        "DELETE" => function ( $parametros ) use ( $controllerProduto ) {
            $controllerProduto->delete( (int) $parametros[0] );
        }
    ],

    "/produto/rank" => [
        "GET" => function () use ( $produtoPersistivel ) {
            $produtos = $produtoPersistivel->rankProdutosMaisVendidos();
            respostaJson( false, "Listagem efetuada com sucesso!", 200, $produtos );
        }
    ]
]
?>