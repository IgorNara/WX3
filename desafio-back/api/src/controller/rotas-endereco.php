<?php
declare(strict_types = 1);

$enderecoPersistivel = new EnderecoPersistivelEmBDR( $conexao );

return [
    "/endereco" => [
        "GET" => function () use ( $enderecoPersistivel ) {
            $enderecos = $enderecoPersistivel->obterTodos();
            respostaJson( false, "Listagem efetuada com sucesso!", 200, $enderecos );
        },

        "POST" => function ( $dados ) use ( $enderecoPersistivel ) {
            $endereco = new Endereco( 0, $dados["logradouro"], $dados["cidade"], $dados["bairro"], $dados["cep"], $dados["numero"], $dados["complemento"] );
            $endereco->validar();

            $problemas = $endereco->getProblemas();
            if( ! empty( $problemas ) ) 
                respostaJson( true, "Erro ao efetuar cadastro - DADOS INVÁLIDOS", 500, $problemas );
            
            $idGerado = $enderecoPersistivel->inserir( $endereco );
            respostaJson( false, "Cadastro efetuado com sucesso! O id gerado foi $idGerado!", 201 );
        },

        "PUT" => function ( $dados ) use ( $enderecoPersistivel ) {
            $endereco = new Endereco( $dados["id"], $dados["logradouro"], $dados["cidade"], $dados["bairro"], $dados["cep"], $dados["numero"], $dados["complemento"] );
            $endereco->validar();

            $problemas = $endereco->getProblemas();
            if( ! empty( $problemas ) ) 
                respostaJson( true, "Erro ao efetuar alteração - DADOS INVÁLIDOS", 500, $problemas );

            $enderecoPersistivel->alterar( $endereco );
            respostaJson( false, "Alteração efetuada com sucesso!", 200 );
        }
    ],

    "/endereco/:id" => [
        "GET" => function ( $parametros ) use ( $enderecoPersistivel ) {
            if( ! $enderecoPersistivel->existeComId( (int) $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );

            respostaJson( false, "Informações listadas com sucesso!", 200, $enderecoPersistivel->obterPeloId( (int) $parametros[0] ) );
        },  

        "DELETE" => function ( $parametros ) use ( $enderecoPersistivel ) {
            if( ! $enderecoPersistivel->existeComId( (int) $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );

            $enderecoPersistivel->excluirPeloId( (int) $parametros[0] );
            respostaJson( false, "Exclusão efetuada com sucesso!", 204 );
        }
    ]
]
?>