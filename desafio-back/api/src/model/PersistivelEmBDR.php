<?php

declare(strict_types = 1);

class PersistivelEmBDR {
    protected PDO $pdo;

    public function __construct ( PDO $pdo ) {
        $this->pdo = $pdo;
    }

    
    protected function carregarObjetosDaClasse( string $sql, string $classe, array $parametros = [], ?string $msgExcecao = null ):array {
        try {
            $ps = $this->pdo->prepare( $sql );
            $ps->setFetchMode( PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $classe );
            $ps->execute( $parametros );
            return $ps->fetchAll();
        }
        catch ( PDOException $erro ) {
            throw new RuntimeException( $msgExcecao ?? $erro->getMessage(), 500, $erro );
        }
    }
    

    protected function primeiroObjetoDaClasse( string $sql, string $classe, array $parametros, ?string $msgExcecao = null ): ?object {
        $objetos = $this->carregarObjetosDaClasse( $sql, $classe, $parametros, $msgExcecao );
        return ( count( $objetos ) > 0 ? $objetos[ 0 ] : null );
    }


    protected function removerRegistroComId( string|int $id, string $tabela, ?string $msgExcecao = null ): bool {
        try {
            $ps = $this->pdo->prepare( "DELETE FROM $tabela WHERE id = :id" );
            $ps->execute( [ 'id' => $id ] );
            return $ps->rowCount() > 0;
        }
        catch ( PDOException $erro ) {
            throw new RuntimeException( $msgExcecao ?? $erro->getMessage(), 500, $erro );
        }
    }


    protected function executar( string $sql, array $parametros, ?string $msgExcecao = null ): PDOStatement {
        try {
            $ps = $this->pdo->prepare( $sql );
            $ps->execute( $parametros );
            return $ps;
        }
        catch ( PDOException $erro ) {
            throw new RuntimeException( $msgExcecao ?? $erro->getMessage(), 500, $erro );
        }
    }


    protected function ultimoIdGerado(): int {
        return (int) $this->pdo->lastInsertId();
    }
}

?>