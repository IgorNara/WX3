<?php
declare( strict_types=1 );


class EnderecoPersistivelEmBDR extends PersistivelEmBDR implements EnderecoPersistivel {

    /** @inheritDoc */
    public function obterTodos(): array {
        $sql = "SELECT * FROM endereco";
        return $this->carregarObjetosDaClasse( $sql, Endereco::class, [], "Erro ao listar endereços." );
    }


    /** @inheritDoc */
    public function inserir( Endereco $endereco ): int {
        $sql = "INSERT INTO endereco ( logradouro, cidade, bairro, numero, cep, complemento ) VALUES ( :logradouro, :cidade, :bairro, :numero, :cep, :complemento )";
        $arrayEndereco = $endereco->jsonSerialize();
        unset( $arrayEndereco["id"] );
        $this->executar( $sql, $arrayEndereco,"Erro ao inserir endereço." );
        return $this->ultimoIdGerado();
    }


    /** @inheritDoc */
    public function alterar( Endereco $endereco ): int {
        $sql = "UPDATE endereco SET logradouro = :logradouro, cidade = :cidade, bairro = :bairro, numero = :numero, cep = :cep, complemento = :complemento WHERE id = :id";
        $ps = $this->executar( $sql, $endereco->jsonSerialize(), "Erro ao alterar endereço." );
        return $ps->rowCount();
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): bool {
        return $this->removerRegistroComId( $id, "endereco", "Erro ao excluir endereço." );
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Endereco {
        $sql = "SELECT * FROM endereco WHERE id = ?";
        return $this->primeiroObjetoDaClasse( $sql, Endereco::class, [ $id ], "Erro ao buscar endereço." );
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        $sql = "SELECT id FROM endereco WHERE id = ?";
        $endereco = $this->primeiroObjetoDaClasse( $sql, Endereco::class, [ $id ], "Erro ao verificar endereço." );
        return $endereco !== null;
    }
}
?>