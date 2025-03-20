<?php
declare( strict_types=1 );


class ClientePersistivelEmBDR extends PersistivelEmBDR implements ClientePersistivel {

    /** @inheritDoc */
    public function obterTodos(): array {
        $sql = "SELECT * FROM cliente";
        $clientes = $this->carregarObjetosDaClasse( $sql, Cliente::class, [], "Erro ao listar clientes." );
        foreach( $clientes as $cliente ) {  
            $cliente->enderecos = json_decode( $cliente->stringEnderecos, true ); 
        }   
        return $clientes;
    }


    /** @inheritDoc */
    public function inserir( Cliente $cliente ): int {
        $sql = "INSERT INTO cliente ( nomeCompleto, cpf, dataNascimento, senha ) VALUES ( :nomeCompleto, :cpf, :dataNascimento, :senha )";
        $arrayCliente = $cliente->toArray();
        $arrayCliente["senha"] = password_hash( $arrayCliente["senha"], PASSWORD_DEFAULT );
        unset( $arrayCliente["id"], $arrayCliente["enderecos"] );
        $this->executar( $sql, $arrayCliente, "Erro ao inserir cliente." );
        return $this->ultimoIdGerado();
    }


    /** @inheritDoc */
    public function alterar( Cliente $cliente ): int {
        $sql = "UPDATE cliente SET nomeCompleto = :nomeCompleto WHERE id = :id";
        $arrayCliente = $cliente->toArray();
        unset( $arrayCliente["cpf"], $arrayCliente["dataNascimento"], $arrayCliente["senha"], $arrayCliente["enderecos"] );
        $ps = $this->executar( $sql, $arrayCliente, "Erro ao alterar cliente." );
        return $ps->rowCount();
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): bool {
        return $this->removerRegistroComId( $id, "cliente", "Erro ao remover cliente." );
    }

    // "SELECT c.id, c.nomeCompleto, c.cpf, c.dataNascimento,
    //                    CONCAT( 
    //                        '[',
    //                        GROUP_CONCAT( 
    //                           JSON_OBJECT( 
    //                              'id', e.id,
    //                              'logradouro', e.logradouro,
    //                              'cidade', e.cidade,
    //                              'bairro', e.bairro,
    //                              'numero', e.numero,
    //                              'cep', e.cep,
    //                              'complemento', e.complemento
    //                           )
    //                        ),
    //                        ']' 
    //                     )AS stringEnderecos
    //                    FROM cliente_endereco ce
    //                    JOIN cliente c ON ( ce.idCliente = c.id ) 
    //                    JOIN endereco e ON ( ce.idEndereco = e.id ) 
    //                    WHERE c.id = ?";
    
    /** @inheritDoc */
    public function obterPeloId( int $id ): Cliente {
        $sql = "SELECT * FROM cliente WHERE id = ?";
        $cliente = $this->primeiroObjetoDaClasse( $sql, Cliente::class, [ $id ], "Erro ao buscar cliente." );
        $cliente->enderecos = json_decode( $cliente->stringEnderecos, true ) ;
        return $cliente;
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        $sql = "SELECT id FROM cliente WHERE id = ?";
        $cliente = $this->primeiroObjetoDaClasse( $sql, Cliente::class, [ $id ], "Erro ao verificar cliente." );
        return $cliente !== null;
    }


    public function logar( Cliente $cliente ): void {
        $sql = "SELECT * FROM cliente WHERE cpf = ?";

        $clienteBd = $this->primeiroObjetoDaClasse( $sql, Cliente::class, [ $cliente->cpf ], "Erro ao fazer login." );

        if( ! $clienteBd || ! password_verify( $cliente->senha, $clienteBd->senha ) ) {
            http_response_code(401);
            throw new RuntimeException( "Usuário não permitido!", 401 );
        }

        if( session_status() === PHP_SESSION_NONE ) {
            session_start();
            $_SESSION["id"] = $cliente->id;
            $_SESSION["usuario"] = $cliente->nomeCompleto;
            $_SESSION["ultima_atividade"] = time();
        }
        
    }
}
?>