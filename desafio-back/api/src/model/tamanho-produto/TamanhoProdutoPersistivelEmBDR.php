<?php
declare( strict_types=1 );


class TamanhoProdutoPersistivelEmBDR extends PersistivelEmBDR implements TamanhoProdutoPersistivel {
    
    /** @inheritDoc */
    public function obterTodos(): array {
        $sql = "SELECT tp.*, 
                       p.idCategoria, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                       c.nome AS categoria, c.descricao AS descricaoCategoria,
                       t.sigla 
                FROM tamanho_produto tp 
                JOIN produto p ON ( tp.idProduto = p.id )
                JOIN categoria c ON ( p.idCategoria = c.id )
                JOIN tamanho t ON ( tp.idTamanho = t.id )";
        $tamanhosProdutos = $this->carregarObjetosDaClasse( $sql, TamanhoProduto::class, [], "Erro ao carregar tamanhos dos produtos." );
        foreach( $tamanhosProdutos as $tp ) {
            $categoria = new Categoria( $tp->idCategoria, $tp->categoria, $tp->descricaoCategoria );
            $produto = new Produto( $tp->idProduto, $categoria, $tp->nome, json_decode( $tp->arrayCores ), json_decode( $tp->arrayUrlImg ), (float) $tp->preco, $tp->descricao, $tp->dataCadastro, (float) $tp->peso );
            $tamanho = new Tamanho( $tp->idTamanho, CampoUnicoTamanho::from( $tp->sigla ) );
            $tp->produto = $produto;
            $tp->tamanho = $tamanho;
        }
        return $tamanhosProdutos;
    }


    /** @inheritDoc */
    public function inserir( TamanhoProduto $tamanhoProduto ): void {
        $sql = "INSERT INTO tamanho_produto ( idProduto, idTamanho, qtd ) VALUES ( :produto, :tamanho, :qtd )";
        $arrayTamanhoProduto = $tamanhoProduto->jsonSerialize();
        $arrayTamanhoProduto["produto"] = $arrayTamanhoProduto["produto"]->id;
        $arrayTamanhoProduto["tamanho"] = $arrayTamanhoProduto["tamanho"]->id;
        $this->executar( $sql, $arrayTamanhoProduto, "Erro ao inserir tamanho do produto." );
    }


    /** @inheritDoc */
    public function alterar( TamanhoProduto $tamanhoProduto ): int {
        $sql = "UPDATE tamanho_produto SET qtd = :qtd WHERE idProduto = :produto AND idTamanho = :tamanho";
        $arrayTamanhoProduto = $tamanhoProduto->jsonSerialize();
        $arrayTamanhoProduto["produto"] = $arrayTamanhoProduto["produto"]->id;
        $arrayTamanhoProduto["tamanho"] = $arrayTamanhoProduto["tamanho"]->id;
        $ps = $this->executar( $sql, $arrayTamanhoProduto, "Erro ao alterar tamanho do produto." );
        return $ps->rowCount();
    }

    
    /** @inheritDoc */
    public function obterPeloId( int $idProduto ): array {
        $sql = "SELECT tp.*, 
                       p.idCategoria, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                       c.nome AS categoria, c.descricao AS descricaoCategoria,
                       t.sigla 
                FROM tamanho_produto tp 
                JOIN produto p ON ( tp.idProduto = p.id )
                JOIN categoria c ON ( p.idCategoria = c.id )
                JOIN tamanho t ON ( tp.idTamanho = t.id )
                WHERE idProduto = ?";
        $tamanhosProduto = $this->carregarObjetosDaClasse( $sql, TamanhoProduto::class, [ $idProduto ], "Erro ao carregar tamanhos do produto." );
        foreach( $tamanhosProduto as $tp ) {
            $categoria = new Categoria( $tp->idCategoria, $tp->categoria, $tp->descricaoCategoria );
            $produto = new Produto( $tp->idProduto, $categoria, $tp->nome, json_decode( $tp->arrayCores ), json_decode( $tp->arrayUrlImg ), (float) $tp->preco, $tp->descricao, $tp->dataCadastro, (float) $tp->peso );
            $tamanho = new Tamanho( $tp->idTamanho, CampoUnicoTamanho::from( $tp->sigla ) );
            $tp->produto = $produto;
            $tp->tamanho = $tamanho;
        }
        return $tamanhosProduto;
    }


    public function obterPeloIdProdutoTamanho( int $idProduto, int $idTamanho ): TamanhoProduto {
        $sql = "SELECT tp.*, 
                        p.idCategoria, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                        c.nome AS categoria, c.descricao AS descricaoCategoria,
                        t.sigla 
                FROM tamanho_produto tp 
                JOIN produto p ON ( tp.idProduto = p.id )
                JOIN categoria c ON ( p.idCategoria = c.id )
                JOIN tamanho t ON ( tp.idTamanho = t.id )
                WHERE idProduto = ? AND idTamanho = ?";

        $tp = $this->primeiroObjetoDaClasse( $sql, TamanhoProduto::class, [ $idProduto, $idTamanho ], "Erro ao carregar tamanhos do produto." );
        $categoria = new Categoria( $tp->idCategoria, $tp->categoria, $tp->descricaoCategoria );
        $produto = new Produto( $tp->idProduto, $categoria, $tp->nome, json_decode( $tp->arrayCores ), json_decode( $tp->arrayUrlImg ), (float) $tp->preco, $tp->descricao, $tp->dataCadastro, (float) $tp->peso );
        $tamanho = new Tamanho( $tp->idTamanho, CampoUnicoTamanho::from( $tp->sigla ) );
        $tp->produto = $produto;
        $tp->tamanho = $tamanho;
        return $tp;
    }


    /** @inheritDoc */
    public function existeComId( int $idProduto ): bool {
        $sql = "SELECT idProduto FROM tamanho_produto WHERE idProduto = ?";
        $tamanhoProduto = $this->primeiroObjetoDaClasse( $sql, TamanhoProduto::class, [ $idProduto ], "Erro ao verificar tamanho do produto." );
        return $tamanhoProduto !== null;
    }
}
?>