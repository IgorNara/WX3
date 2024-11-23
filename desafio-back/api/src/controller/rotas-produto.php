<?php
declare(strict_types = 1);

$produtoPersistivel = new ProdutoPersistivelEmBDR( $conexao );
$tamanhoProdutoPersistivel = new TamanhoProdutoPersistivelEmBDR( $conexao );

// formato esperado em $dados = {
//    id: 0, // id e os outros dados do produto, quando necessário
//    tamanhos: {
//        {
//            id: 0, // id do tamanho
//            qtd: 0 // quantidade de estoque para esse tamanho
//        }, 
//        Todos os outros tamanhos presentes no enum também devem ser enviados, mesmo com o estoque = 0
//    }
// }

return [
    "/produto" => [
        "GET" => function () use ( $tamanhoProdutoPersistivel ) {
            $tamanhosProdutos = $tamanhoProdutoPersistivel->obterTodos();
            respostaJson( false, "Listagem efetuada com sucesso!", 200, $tamanhosProdutos );
        },

        "POST" => function ( $dados ) use ( $produtoPersistivel, $tamanhoProdutoPersistivel ) {
            $categoria = new Categoria( $dados["categoria"]["id"] );

            $respostaIdProduto = gerarId( "produto" );
            if( $respostaIdProduto["erro"] )
                respostaJson( true, $respostaIdProduto["msg"], 500 );
            
            $produto = new Produto( $respostaIdProduto["id"], $categoria, $dados["nome"], $dados["cores"], $dados["urls"], $dados["preco"], $dados["descricao"], $dados["dataCadastro"], $dados["peso"] );            
            $produto->validar();

            $problemasProduto = $produto->getProblemas();
            if( ! empty( $problemasProduto ) )
                respostaJson( true, "Erro ao efetuar cadastro - DADOS INVÁLIDOS", 500, $problemasProduto );

            $urls = [];
            foreach( $produto->urls as $url ) {
                $urls[] = salvarImg( $url, $respostaIdProduto["id"], "../api/imagens/produtos/" );
            }
            $produto->urls = $urls;

            $dadosTamanhos = $dados["tamanhos"];
            foreach( $dadosTamanhos as $dadosTamanho ) {
                $tamanho = new Tamanho( $dadosTamanho["id"] );
                $tamanhoProduto = new TamanhoProduto( $produto, $tamanho, $dadosTamanho["qtd"] );
                $tamanhoProduto->validar();

                $problemasTamanhoProduto = $tamanhoProduto->getProblemas();
                if( ! empty( $problemas ) )
                    respostaJson( true, "Erro ao efetuar cadastro - DADOS INVÁLIDOS", 500, $problemasTamanhoProduto );

                $tamanhoProdutoPersistivel->inserir( $tamanhoProduto );
            }

            $idGerado = $produtoPersistivel->inserir( $produto );
            respostaJson( false, "Cadastro efetuado com sucesso! O id gerado foi $idGerado!", 201 );
        },

        "PUT" => function ( $dados ) use ( $produtoPersistivel, $tamanhoProdutoPersistivel ) {
            $categoria = new Categoria( $dados["idCategoria"] );
            
            $produtoBd = $produtoPersistivel->obterPeloId( $dados["id"] );
            foreach( $produtoBd->urls as $url ) {
                excluirImg( $url );
            }

            $produto = new Produto( $dados["id"], $categoria, $dados["nome"], $dados["cores"], $dados["urls"], $dados["preco"], $dados["descricao"], $dados["dataCadastro"], $dados["peso"] );
            $produto->validar();

            $problemas = $produto->getProblemas();
            if( ! empty( $problemas ) )
                respostaJson( true, "Erro ao efetuar alteração - DADOS INVÁLIDOS", 500, $problemas );
            
            $urls = [];
            foreach( $produto->urls as $url ) {
                $urls[] = salvarImg( $url, $produto->id, "../api/imagens/produtos/" );
            }
            $produto->urls = $urls;

            $dadosTamanhos = $dados["tamanhos"];
            foreach( $dadosTamanhos as $dadosTamanho ) {
                $tamanho = new Tamanho( $dadosTamanho["id"] );
                $tamanhoProduto = $tamanhoProdutoPersistivel->obterPeloId( $produto->id, $tamanho->id );
                $tamanhoProduto->qtd += $dadosTamanho["qtd"];
                $tamanhoProduto->validar();

                $problemasTamanhoProduto = $tamanhoProduto->getProblemas();
                if( ! empty( $problemas ) )
                    respostaJson( true, "Erro ao efetuar alteração - DADOS INVÁLIDOS", 500, $problemasTamanhoProduto );

                $tamanhoProdutoPersistivel->alterar( $tamanhoProduto );
            }

            $produtoPersistivel->alterar( $produto );
            respostaJson( false, "Alteração efetuada com sucesso!", 200 );
        }
    ],

    "/produto/:id" => [
        /** Envia todos os tamanhos de um produto em formato json */
        "GET" => function ( $parametros ) use ( $tamanhoProdutoPersistivel ) { 
            if( ! $tamanhoProdutoPersistivel->existeComIdProduto( $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );

            respostaJson( false, "Informações listadas com sucesso!", 200, $tamanhoProdutoPersistivel->obterPeloIdProduto( $parametros[0] ) );
        },

        /** Deleta o produto e, consequentemente, todas as relações dele com qualquer tamanho */
        "DELETE" => function ( $parametros ) use ( $produtoPersistivel ) {
            if( ! $produtoPersistivel->existeComId( $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );

            $produto = $produtoPersistivel->obterPeloId( $parametros[0] );
            
            // Deleta as imagens do produto a ser excluído
            foreach( $produto->urls as $url ) {
                excluirImg( $url );
            }
            
            $produtoPersistivel->excluirPeloId( $parametros[0] );
            respostaJson( false, "Exclusão efetuada com sucesso!", 204 );
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