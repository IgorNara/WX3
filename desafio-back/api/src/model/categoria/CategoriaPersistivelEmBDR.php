<?php
declare( strict_types=1 );


class CategoriaPersistivelEmBDR extends PersistivelEmBDR implements CategoriaPersistivel {

    /** @inheritDoc */
    public function obterTodos(): array {
        $sql = "SELECT * FROM categoria";
        return $this->carregarObjetosDaClasse( $sql, Categoria::class, [], "Erro ao listar categorias." );
    }


    /** @inheritDoc */
    public function inserir( Categoria $categoria ): int {
        $sql = "INSERT INTO categoria ( nome, descricao ) VALUES ( :nome, :descricao )";
        $arrayCategoria = $categoria->toArray();
        unset( $arrayCategoria["id"] );
        $this->executar( $sql, $arrayCategoria, "Erro ao inserir categoria." );
        return $this->ultimoIdGerado();
    }


    /** @inheritDoc */
    public function alterar( Categoria $categoria ): bool {
        $sql = "UPDATE categoria SET nome = :nome, descricao = :descricao WHERE id = :id";
        $ps = $this->executar( $sql, $categoria->toArray(), "Erro ao alterar categoria." );
        return $ps->rowCount() > 0;          
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): bool {
        return $this->removerRegistroComId( $id, "categoria", "Erro ao excluir categoria." );
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Categoria {
        $sql = "SELECT * FROM categoria WHERE id = ?";
        return $this->primeiroObjetoDaClasse( $sql, Categoria::class, [ $id ], "Erro ao buscar categoria." );
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        $sql = "SELECT * FROM categoria WHERE id = ?";
        $categoria = $this->primeiroObjetoDaClasse( $sql, Categoria::class, [ $id ], "Erro ao verificar categoria." );
        return $categoria !== null;
    }
}
?>