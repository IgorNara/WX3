<?php
declare( strict_types=1 );


class VendaPersistivelEmBDR implements VendaPersistivel {
    private PDO $conexao;

    public function __construct ( PDO $conexao ) {
        $this->conexao = $conexao;
    }


    /** @inheritDoc */
    public function inserir( Venda $venda ): int {
        $formaPagamento = $venda->formaPagamento->value;
        try {
            $sql = "INSERT INTO venda ( idCliente, idEndereco, valorTotal, valorFrete, percentualDesconto, formaPagamento ) VALUES ( ?, ?, ?, ?, ?, ? )";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $venda->cliente->id );
            $ps->bindParam( 2, $venda->endereco->id );
            $ps->bindParam( 3, $venda->valorTotal );
            $ps->bindParam( 4, $venda->valorFrete );
            $ps->bindParam( 5, $venda->percentualDesconto );
            $ps->bindParam( 6, $formaPagamento );
            $ps->execute();

            return intval( $this->conexao->lastInsertId() );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao inserir venda - ".$erro->getMessage() );
        }  
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Venda {
        try {
            $sql = "SELECT v.*,
                           c.nomeCompleto, c.cpf, c.dataNascimento,
                           e.logradouro, e.cidade, e.bairro, e.numero, e.cep, e.complemento
                    FROM venda v 
                    JOIN cliente c ON ( v.idCliente = c.id )
                    JOIN endereco e ON ( v.idEndereco = e.id ) WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            $resposta = $ps->fetch();

            $cliente = new Cliente( (int) $resposta["idCliente"], $resposta["nomeCompleto"], $resposta["cpf"], $resposta["dataNascimento"] );
            $endereco = new Endereco( (int) $resposta["idEndereco"], $resposta["logradouro"], $resposta["cidade"], $resposta["bairro"], $resposta["cep"], (int) $resposta["numero"], $resposta["complemento"] );

            $venda = new Venda( 
                $resposta["id"],  
                $cliente, 
                $endereco, 
                FormaPagamento::from( $resposta["formaPagamento"] )
            );
            $venda->valorTotal = $resposta["valorTotal"];
            return $venda;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar venda - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        try {
            $sql = "SELECT id FROM venda WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount() > 0;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao verificar venda - ".$erro->getMessage() );
        }
    }
}
?>