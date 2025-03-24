<?php

declare(strict_types=1);

class ClienteEnderecoPersistivelEmBDR extends PersistivelEmBDR implements ClienteEnderecoPersistivel {

    /** @inheritDoc */
    public function obterPeloId( int $idCliente ): array {
        $sql = "SELECT e.*
                FROM cliente_endereco ce
                JOIN cliente c ON ( ce.idCliente = c.id ) 
                JOIN endereco e ON ( ce.idEndereco = e.id )
                WHERE c.id = ?";
        return $this->carregarObjetosDaClasse( $sql, Endereco::class, [ $idCliente ], "Erro ao buscar endereços do cliente." );
    }
    
    /** @inheritDoc */
    public function inserir( ClienteEndereco $clienteEndereco ): int {
        $sql = "INSERT INTO cliente_endereco ( idCliente, idEndereco ) VALUES ( :cliente, :endereco )";
        $arrayClienteEndereco = $clienteEndereco->toArray();
        $arrayClienteEndereco["cliente"] = $clienteEndereco->cliente->id;
        $arrayClienteEndereco["endereco"] = $clienteEndereco->endereco->id;
        $this->executar( $sql, $arrayClienteEndereco, "Erro ao inserir relação entre cliente e endereço." );
        return 1;
    }

    /** @inheritDoc */
    public function existeComId(int $idCliente): bool {
        $sql = "SELECT * FROM cliente_endereco WHERE idCliente = ?";
        $cliente = $this->primeiroObjetoDaClasse( $sql, Cliente::class, [ $idCliente ], "Erro ao verificar relação entre o Cliente e algum endereço." );
        return $cliente !== null;
    }
}

?>