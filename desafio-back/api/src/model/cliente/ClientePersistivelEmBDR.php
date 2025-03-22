<?php
declare( strict_types=1 );


class ClientePersistivelEmBDR extends PersistivelEmBDR implements ClientePersistivel {

    /** @inheritDoc */
    public function obterTodos(): array {
        $sql = "SELECT * FROM cliente";
        return $this->carregarObjetosDaClasse( $sql, Cliente::class, [], "Erro ao listar clientes." ); 
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
    public function alterar( Cliente $cliente ): bool {
        $sql = "UPDATE cliente SET nomeCompleto = :nomeCompleto WHERE id = :id";
        $arrayCliente = $cliente->toArray();
        unset( $arrayCliente["cpf"], $arrayCliente["dataNascimento"], $arrayCliente["senha"], $arrayCliente["enderecos"] );
        $ps = $this->executar( $sql, $arrayCliente, "Erro ao alterar cliente." );
        return $ps->rowCount() > 0;
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): bool {
        return $this->removerRegistroComId( $id, "cliente", "Erro ao remover cliente." );
    }

    
    /** @inheritDoc */
    public function obterPeloId( int $id ): ?Cliente {
        $sql = "SELECT * FROM cliente WHERE id = ?";
        return $this->primeiroObjetoDaClasse( $sql, Cliente::class, [ $id ], "Erro ao buscar cliente." );
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
            $_SESSION["user_id"] = $clienteBd->id;
            $_SESSION["user_name"] = $clienteBd->nomeCompleto;
            $_SESSION["last_activity"] = time();
        }        
    }
}
?>