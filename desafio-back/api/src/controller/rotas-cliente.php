<?php
declare(strict_types = 1);

$clientePersistivel = new ClientePersistivelEmBDR( $conexao );

return [
    "/cliente/logar" => [
        "POST" => function ( $dados ) use ( $clientePersistivel ) {
            $cliente = new Cliente( 0, "", $dados["cpf"], "", $dados["senha"] );
            $clientePersistivel->logar( $cliente );
            respostaJson( false, "Login efetuado com sucesso!", 200 );
        }
    ],

    "/cliente" => [
        "GET" => function () use ( $clientePersistivel ) {
            $clientes = $clientePersistivel->obterTodos();
            respostaJson( false, "Listagem efetuada com sucesso!", 200, $clientes );
        },

        "POST" => function ( $dados ) use ( $clientePersistivel ) {
            $cliente = new Cliente( 0, $dados["nomeCompleto"], $dados["cpf"], $dados["dataNascimento"], $dados["senha"] );
            $cliente->validar();
            $cliente->validarSenha();

            $problemas = $cliente->getProblemas();
            if( ! empty( $problemas ) )
                respostaJson( true, "Erro ao efetuar cadastro - DADOS INVÁLIDOS", 500, $problemas );

            $idGerado = $clientePersistivel->inserir( $cliente );
            respostaJson( false, "Cadastro efetuado com sucesso! O id gerado foi $idGerado!", 201 );
        },

        "PUT" => function ( $dados ) use ( $clientePersistivel ) {
            $cliente = new Cliente( $dados["id"], $dados["nomeCompleto"], $dados["cpf"], $dados["dataNascimento"] );
            $cliente->validar();

            $problemas = $cliente->getProblemas();
            if( ! empty( $problemas ) )
                respostaJson( true, "Erro ao efetuar alteração - DADOS INVÁLIDOS", 500, $problemas );

            $clientePersistivel->alterar( $cliente );
            respostaJson( false, "Alteração efetuada com sucesso!", 200 );
        }
    ],

    "/cliente/:id" => [
        "GET" => function ( $parametros ) use ( $clientePersistivel ) {
            if( ! $clientePersistivel->existeComId( $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );

            respostaJson( false, "Informações listadas com sucesso!", 200, $clientePersistivel->obterPeloId( $parametros[0] ) );
        },

        "DELETE" => function ( $parametros ) use ( $clientePersistivel ) {
            if( ! $clientePersistivel->existeComId( $parametros[0] ) )
                respostaJson( true, "Informações não encontradas!", 400 );

            $clientePersistivel->excluirPeloId( $parametros[0] );
            respostaJson( false, "Exclusão efetuada com sucesso!", 204 );
        }
    ]
]
?>