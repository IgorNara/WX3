<?php
declare(strict_types = 1);

$categoriaPersistivel = new CategoriaPersistivelEmBDR( $conexao );

return [
    "/categoria" => [
        "GET" => function () use ( $categoriaPersistivel ) {
            $categorias = $categoriaPersistivel->obterTodos();
            respostaJson( false, "Listagem efetuada com sucesso!", 200, $categorias );
        },

        "POST" => function ( $dados ) use ( $categoriaPersistivel ) {
            $categoria = new Categoria( 0, $dados["nome"], $dados["descricao"] );
            $categoria->validar();

            $problemas = $categoria->getProblemas();
            if( ! empty( $problemas ) )
                respostaJson( false, "Erro ao efetuar cadastro - DADOS INVÁLIDOS", 500, $problemas );

            $idGerado = $categoriaPersistivel->inserir( $categoria );
            respostaJson( false, "Cadastro efetuado com sucesso! O id gerado foi $idGerado!", 201 );
        },

        "PUT" => function ( $dados ) use ( $categoriaPersistivel ) {
            $categoria = new Categoria( $dados["id"], $dados["nome"], $dados["descricao"] );
            $categoria->validar();

            $problemas = $categoria->getProblemas();
            if( ! empty( $problemas ) ) 
                respostaJson( true, "Erro ao efetuar alteração - DADOS INVÁLIDOS", 500, $problemas );

            $categoriaPersistivel->alterar( $categoria );
            respostaJson( false, "Alteração efetuada com sucesso!", 200 );
        }
    ],

    "/categoria/:id" => [
        "GET" => function ( $parametros ) use ( $categoriaPersistivel ) {
            if( ! $categoriaPersistivel->existeComId( $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );

            respostaJson( false, "Informações listadas com sucesso!", 200, $categoriaPersistivel->obterPeloId( $parametros[0] ) );
        },

        "DELETE" => function ( $parametros ) use ( $categoriaPersistivel ) {
            if( ! $categoriaPersistivel->existeComId( $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );
        
            $categoriaPersistivel->excluirPeloId( $parametros[0] );
            respostaJson( false, "Exclusão efetuada com sucesso!", 204 );
        }
    ]
]
?>