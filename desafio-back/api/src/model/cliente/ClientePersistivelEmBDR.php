<?php
declare( strict_types=1 );


class ClientePersistivelEmBDR implements ClientePersistivel {
    private PDO $conexao;

    public function __construct ( PDO $conexao ) {
        $this->conexao = $conexao;
    }


    /** @inheritDoc */
    public function obterTodos(): array {
        $clientes = [];
        try {
            $sql = "SELECT id, nomeCompleto, cpf, dataNascimento FROM cliente";

            $ps = $this->conexao->prepare( $sql );
            $ps->execute();

            $resposta = $ps->fetchAll();
            foreach( $resposta as $cliente ) {
                $clientes[] = new Cliente( 
                    (int) $cliente["id"],
                    $cliente["nomeCompleto"],
                    $cliente["cpf"],
                    $cliente["dataNascimento"]
                );
            }
            return $clientes;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao listar clientes - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function inserir( Cliente $cliente ): int {
        $senhaCriptografada = password_hash($cliente->senha, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO cliente ( nomeCompleto, cpf, dataNascimento, senha ) VALUES ( ?, ?, ?, ? )";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $cliente->nomeCompleto );
            $ps->bindParam( 2, $cliente->cpf );
            $ps->bindParam( 3, $cliente->dataNascimento );
            $ps->bindParam( 4, $senhaCriptografada );
            $ps->execute();

            return intval( $this->conexao->lastInsertId() );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao inserir cliente - ".$erro->getMessage() );
        }  
    }


    /** @inheritDoc */
    public function alterar( Cliente $cliente ): int {
        try {
            $sql = "UPDATE cliente SET nomeCompleto = ? WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $cliente->nomeCompleto );
            $ps->bindParam( 2, $cliente->id );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao alterar cliente - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): int {
        try {
            $sql = "DELETE FROM cliente WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao excluir cliente - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Cliente {
        try {
            $sql = "SELECT * FROM cliente WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            $resposta = $ps->fetch();

            return new Cliente( (int) $resposta["id"], $resposta["nomeCompleto"], $resposta["cpf"], $resposta["dataNascimento"] );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar cliente - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        try {
            $sql = "SELECT id FROM cliente WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount() > 0;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao verificar cliente - ".$erro->getMessage() );
        }
    }


    public function logar( Cliente $cliente ): void {
        try{
            $sql = "SELECT * FROM cliente WHERE cpf = ?";
    
            $ps = $this->conexao->prepare($sql);
            $ps->bindParam(1, $cliente->cpf);
            $ps->execute();
    
            $resposta = $ps->fetch();
    
            if( !( $ps->rowCount() > 0 ) || !password_verify( $cliente->senha, $resposta["senha"] ) ) {
                http_response_code(401);
                throw new Exception( 'Usuário inválido.');
            }
    
            if( session_status() === PHP_SESSION_NONE ) {
                session_start();
                $_SESSION["id"] = $cliente["id"];
                $_SESSION["usuario"] = $cliente["nome"];
                $_SESSION["ultima_atividade"] = time();
            }
        }
        catch(PDOException $erro){
            throw new RuntimeException( "Erro ao fazer login - ".$erro->getMessage() );
        }
    }
}
?>