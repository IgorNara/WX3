<?php
declare( strict_types=1 );


class TamanhoProdutoPersistivelEmBDR implements TamanhoProdutoPersistivel {
    private PDO $conexao;

    public function __construct ( PDO $conexao ) {
        $this->conexao = $conexao;
    }


    /** @inheritDoc */
    public function obterTodos(): array {
        $tamanhosProdutos = [];
        try {
            $sql = "SELECT tp.*, 
                           p.idCategoria, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                           c.nome AS categoria, c.descricao AS descricaoCategoria,
                           t.sigla 
                    FROM tamanho_produto tp 
                    JOIN produto p ON ( tp.idProduto = p.id )
                    JOIN categoria c ON ( p.idCategoria = c.id )
                    JOIN tamanho t ON ( tp.idTamanho = t.id )";

            $ps = $this->conexao->prepare( $sql );
            $ps->execute();


            $resposta = $ps->fetchAll();
            foreach( $resposta as $tp ) {
                $categoria = new Categoria( (int) $tp["idCategoria"], $tp["categoria"], $tp["descricaoCategoria"] );
                $produto = new Produto( (int) $tp["idProduto"], $categoria, $tp["nome"], (array) $tp["arrayCores"], ( array ) $tp["arrayUrlImg"], (float) $tp["preco"], $tp["descricao"], $tp["dataCadastro"], (float) $tp["peso"] );
                $tamanho = new Tamanho( (int) $tp["idTamanho"], CampoUnicoTamanho::from( $tp["sigla"] ) );

                $tamanhosProdutos[] = new TamanhoProduto( $produto, $tamanho, (int) $tp["qtd"] );
            }
            return $tamanhosProdutos;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar produtos e seus tamanhos - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function inserir( TamanhoProduto $tamanhoProduto ): void {
        try {
            $sql = "INSERT INTO tamanho_produto ( idProduto, idTamanho, qtd ) VALUES ( ?, ?, ? )";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $tamanhoProduto->produto->id );
            $ps->bindParam( 2, $tamanhoProduto->tamanho->id );
            $ps->bindParam( 3, $tamanhoProduto->qtd );
            $ps->execute();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao inserir tamanho no produto - ".$erro->getMessage() );
        }  
    }


    /** @inheritDoc */
    public function alterar( TamanhoProduto $tamanhoProduto ): int {
        try {
            $sql = "UPDATE tamanho_produto SET qtd = ? WHERE idProduto = ? AND idTamanho = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $tamanhoProduto->qtd );
            $ps->bindParam( 2, $tamanhoProduto->produto->id );
            $ps->bindParam( 3, $tamanhoProduto->tamanho->id );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao alterar tamanho do produto - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function obterPeloId( int $idProduto, int $idTamanho ): TamanhoProduto {
        try {
            $sql = "SELECT tp.*, 
                           p.idCategoria, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                           c.nome AS categoria, c.descricao AS descricaoCategoria,
                           t.sigla 
                    FROM tamanho_produto tp 
                    JOIN produto p ON ( tp.idProduto = p.id )
                    JOIN categoria c ON ( p.idCategoria = c.id )
                    JOIN tamanho t ON ( tp.idTamanho = t.id )
                    WHERE idProduto = ? AND idTamanho = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $idProduto );
            $ps->bindParam( 2, $idTamanho );
            $ps->execute();

            $resposta = $ps->fetch();

            $categoria = new Categoria( (int) $resposta["idCategoria"], $resposta["categoria"], $resposta["descricaoCategoria"] );
            $produto = new Produto( $resposta["idProduto"], $categoria, $resposta["nome"], (array) $resposta["arrayCores"], ( array ) $resposta["arrayUrlImg"], (float) $resposta["preco"], $resposta["descricao"], $resposta["dataCadastro"], (float) $resposta["peso"] );
            $tamanho = new Tamanho( $resposta["idTamanho"], CampoUnicoTamanho::from( $resposta["sigla"] ) );
            return new TamanhoProduto( $produto, $tamanho, (int) $resposta["qtd"] );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar tamanhos do produto - ".$erro->getMessage() );
        }
    }

    /** @inheritDoc */
    public function obterPeloIdProduto( int $idProduto ): array {
        $tamanhosProduto = [];
        try {
            $sql = "SELECT tp.*, 
                           p.idCategoria, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                           c.nome AS categoria, c.descricao AS descricaoCategoria,
                           t.sigla 
                    FROM tamanho_produto tp 
                    JOIN produto p ON ( tp.idProduto = p.id )
                    JOIN categoria c ON ( p.idCategoria = c.id )
                    JOIN tamanho t ON ( tp.idTamanho = t.id )
                    WHERE idProduto = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $idProduto );
            $ps->execute();

            $resposta = $ps->fetchAll();
            foreach( $resposta as $tp ) {
                $categoria = new Categoria( (int) $tp["idCategoria"], $tp["categoria"], $tp["descricaoCategoria"] );
                $produto = new Produto( (int) $tp["idProduto"], $categoria, $tp["nome"], (array) $tp["arrayCores"], ( array ) $tp["arrayUrlImg"], (float) $tp["preco"], $tp["descricao"], $tp["dataCadastro"], (float) $tp["peso"] );
                $tamanho = new Tamanho( (int) $tp["idTamanho"], CampoUnicoTamanho::from( $tp["sigla"] ) );
                $tamanhosProduto[] = new TamanhoProduto( $produto, $tamanho, (int) $tp["qtd"] );
            }
           
            return $tamanhosProduto;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar tamanhos do produto - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function existeComIdProduto( int $idProduto ): bool {
        try {
            $sql = "SELECT idProduto FROM tamanho_produto WHERE idProduto = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $idProduto );
            $ps->execute();

            return $ps->rowCount() > 0;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao verificar tamanho do produto - ".$erro->getMessage() );
        }
    }
}
?>