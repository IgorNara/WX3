<?php
declare( strict_types=1 );


class CategoriaPersistivelEmBDR implements CategoriaPersistivel {
    private PDO $conexao;

    public function __construct ( PDO $conexao ) {
        $this->conexao = $conexao;
    }


    /** @inheritDoc */
    public function obterTodos(): array {
        $categorias = [];
        try {
            $sql = "SELECT * FROM categoria";

            $ps = $this->conexao->prepare( $sql );
            $ps->execute();

            $resposta = $ps->fetchAll();
            foreach( $resposta as $categoria ) {
                $categorias[] = new Categoria( (int) $categoria["id"], $categoria["nome"], $categoria["descricao"] );
            }

            return $categorias;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao listar categorias - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function inserir( Categoria $categoria ): int {
        try {
            $sql = "INSERT INTO categoria ( nome, descricao ) VALUES ( ?, ? )";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $categoria->nome );
            $ps->bindParam( 2, $categoria->descricao );
            $ps->execute();

            return intval( $this->conexao->lastInsertId() );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao inserir categoria - ".$erro->getMessage() );
        }  
    }


    /** @inheritDoc */
    public function alterar( Categoria $categoria ): int {
        try {
            $sql = "UPDATE categoria SET nome = ?, descricao = ? WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $categoria->nome );
            $ps->bindParam( 2, $categoria->descricao );
            $ps->bindParam( 3, $categoria->id );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao alterar categoria - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): int {
        try {
            $sql = "DELETE FROM categoria WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao excluir categoria - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Categoria {
        try {
            $sql = "SELECT * FROM categoria WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            $resposta = $ps->fetch();

            return new Categoria( (int) $id, $resposta["nome"], $resposta["descricao"] );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar categoria - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        try {
            $sql = "SELECT id FROM categoria WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount() > 0;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao verificar categoria - ".$erro->getMessage() );
        }
    }
}
?>