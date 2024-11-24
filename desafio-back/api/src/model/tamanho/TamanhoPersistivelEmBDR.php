<?php
declare( strict_types=1 );


class TamanhoPersistivelEmBDR implements TamanhoPersistivel {
    private PDO $conexao;

    public function __construct ( PDO $conexao ) {
        $this->conexao = $conexao;
    }


    /** @inheritDoc */
    public function obterTodos(): array {
        $tamanhos = [];
        try {
            $sql = "SELECT * FROM tamanho";

            $ps = $this->conexao->prepare( $sql );
            $ps->execute();

            $resposta = $ps->fetchAll();
            foreach( $resposta as $tamanho ) {
                $tamanhos[] = new Tamanho( (int) $tamanho["id"], CampoUnicoTamanho::from( $tamanho["sigla"] ) );
            }
            return $tamanhos;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao listar tamanhos - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Tamanho {
        try {
            $sql = "SELECT * FROM tamanho WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            $resposta = $ps->fetch();

            return new Tamanho( (int) $resposta["id"], CampoUnicoTamanho::from( $resposta["sigla"] ) );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar tamanho - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        try {
            $sql = "SELECT id FROM tamanho WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount() > 0;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao verificar tamanho - ".$erro->getMessage() );
        }
    }
}
?>