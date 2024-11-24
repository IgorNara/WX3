<?php
declare( strict_types=1 );


class EnderecoPersistivelEmBDR implements EnderecoPersistivel {
    private PDO $conexao;

    public function __construct ( PDO $conexao ) {
        $this->conexao = $conexao;
    }


    /** @inheritDoc */
    public function obterTodos(): array {
        $enderecos = [];
        try {
            $sql = "SELECT * FROM endereco";

            $ps = $this->conexao->prepare( $sql );
            $ps->execute();

            $resposta = $ps->fetchAll();
            foreach( $resposta as $endereco ){
                $enderecos[] = new Endereco( 
                    (int) $endereco["id"], 
                    $endereco["logradouro"],
                    $endereco["cidade"],
                    $endereco["bairro"],
                    $endereco["cep"],
                    (int) $endereco["numero"],
                    $endereco["complemento"]
                );
            }
            return $enderecos;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao listar endereços - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function inserir( Endereco $endereco ): int {
        try {
            $sql = "INSERT INTO endereco ( logradouro, cidade, bairro, numero, cep, complemento ) VALUES ( ?, ?, ?, ?, ?, ? )";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $endereco->logradouro );
            $ps->bindParam( 2, $endereco->cidade );
            $ps->bindParam( 3, $endereco->bairro );
            $ps->bindParam( 4, $endereco->numero );
            $ps->bindParam( 5, $endereco->cep );
            $ps->bindParam( 6, $endereco->complemento );
            $ps->execute();

            return intval( $this->conexao->lastInsertId() );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao inserir endereço - ".$erro->getMessage() );
        }  
    }


    /** @inheritDoc */
    public function alterar( Endereco $endereco ): int {
        try {
            $sql = "UPDATE endereco SET logradouro = ?, cidade = ?, bairro = ?, numero = ?, cep = ?, complemento = ? WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $endereco->logradouro);
            $ps->bindParam( 2, $endereco->cidade);
            $ps->bindParam( 3, $endereco->bairro);
            $ps->bindParam( 4, $endereco->numero);
            $ps->bindParam( 5, $endereco->cep);
            $ps->bindParam( 6, $endereco->complemento);
            $ps->bindParam( 7, $endereco->id);
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao alterar endereço - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): int {
        try {
            $sql = "DELETE FROM endereco WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao excluir endereço - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Endereco {
        try {
            $sql = "SELECT * FROM endereco WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            $resposta = $ps->fetch();

            return new Endereco( 
                (int) $id, 
                $resposta["logradouro"], 
                $resposta["cidade"], 
                $resposta["bairro"],
                $resposta["cep"], 
                (int) $resposta["numero"], 
                $resposta["complemento"]
            );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar endereço - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        try {
            $sql = "SELECT id FROM endereco WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount() > 0;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao verificar endereço - ".$erro->getMessage() );
        }
    }
}
?>