<?php
declare( strict_types=1 );


class ProdutoPersistivelEmBDR implements ProdutoPersistivel {
    private PDO $conexao;

    public function __construct ( PDO $conexao ) {
        $this->conexao = $conexao;
    }



    /** @inheritDoc */
    public function inserir( Produto $produto ): int {
        try {    
            $respostaIdProduto = gerarId( "produto" );
            if( $respostaIdProduto["erro"] ) 
                respostaJson( true, $respostaIdProduto["msg"], 500 );

            $sql = "INSERT INTO produto ( id, idCategoria, nome, arrayCores, arrayUrlImg, preco, descricao, dataCadastro, peso ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ? )";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $produto->id );
            $ps->bindParam( 2, $produto->categoria->id );
            $ps->bindParam( 3, $produto->nome );
            $ps->bindParam( 4, json_encode( $produto->cores ) );
            $ps->bindParam( 5, json_encode( $produto->urls, JSON_UNESCAPED_SLASHES ) );
            $ps->bindParam( 6, $produto->preco );
            $ps->bindParam( 7, $produto->descricao );
            $ps->bindParam( 8, $produto->dataCadastro );
            $ps->bindParam( 9, $produto->peso );
            $ps->execute();

            return intval( $this->conexao->lastInsertId() );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao inserir produto - ".$erro->getMessage() );
        }  
    }


    /** @inheritDoc */
    public function alterar( Produto $produto ): int {
        try {
            $sql = "UPDATE produto SET idCategoria = ?, nome = ?, arrayCores = ?, arrayUrlImg = ?, preco = ?, descricao = ?, dataCadastro = ?, peso = ? WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $produto->categoria->id );
            $ps->bindParam( 2, $produto->nome );
            $ps->bindParam( 3, json_encode( $produto->cores ) );
            $ps->bindParam( 4, json_encode( $produto->urls ) );
            $ps->bindParam( 5, $produto->preco );
            $ps->bindParam( 6, $produto->descricao );
            $ps->bindParam( 7, $produto->dataCadastro );
            $ps->bindParam( 8, $produto->peso );
            $ps->bindParam( 9, $produto->id );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao alterar produto - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): int {
        try {
            $sql = "DELETE FROM produto WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao excluir produto - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Produto {
        try {
            $sql = "SELECT p.*, c.nome AS categoria, c.descricao AS descricaoCategoria 
                    FROM produto p JOIN categoria c ON ( p.idCategoria = c.id ) WHERE p.id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            $resposta = $ps->fetch();

            $categoria = new Categoria( (int) $resposta["idCategoria"], $resposta["categoria"], $resposta["descricaoCategoria"] );

            return new Produto( 
                (int) $resposta["id"],
                $categoria,
                $resposta["nome"],
                (array) $resposta["arrayCores"],
                (array) $resposta["arrayUrlImg"],
                (float) $resposta["preco"],
                $resposta["descricao"],
                $resposta["dataCadastro"],
                (float) $resposta["peso"]
            );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar produto - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        try {
            $sql = "SELECT id FROM produto WHERE id = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $id );
            $ps->execute();

            return $ps->rowCount() > 0;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao verificar produto - ".$erro->getMessage() );
        }
    }


    /**
     * Retorna uma lista de produtos ordenada em ordem decrascente com base no seu total de vendas
     * 
     * @return array<Produto>
     * @throws RuntimeException
     */
    public function rankProdutosMaisVendidos(): array {
        $produtos = [];
        try {
            $sql = "SELECT p.id, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                           p.idCategoria, c.nome AS categoria, c.descricao AS descricaoCategoria
                    FROM venda_produto_tamanho vpt
                    JOIN produto p ON ( vpt.idProduto = p.id )
                    JOIN categoria c ON ( p.idCategoria = c.id )
                    GROUP BY p.id, c.id ORDER BY SUM( vpt.qtd ) DESC"; // LIMIT ?
            
            $ps = $this->conexao->prepare( $sql );
            $ps->execute();

            $resposta = $ps->fetchAll();
            foreach( $resposta as $produto ) {
                $categoria = new Categoria( (int) $produto["idCategoria"], $produto["categoria"], $produto["descricaoCategoria"] );
                
                $produtos[] = new Produto( 
                    (int) $produto["id"],
                    $categoria,
                    $produto["nome"],
                    (array) $produto["arrayCores"],
                    (array) $produto["arrayUrlImg"],
                    (float) $produto["preco"],
                    $produto["descricao"],
                    $produto["dataCadastro"],
                    (float) $produto["peso"]
                );
            }
            return $produtos;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao listar rank dos produtos mais vendidos - ".$erro->getMessage() );
        }
    }
}
?>