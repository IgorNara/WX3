<?php
declare( strict_types=1 );


class ProdutoPersistivelEmBDR extends PersistivelEmBDR implements ProdutoPersistivel {
    /** @inheritDoc */
    public function obterTodos(): array {
        $sql = "SELECT p.id, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                       c.id AS idCategoria, c.nome AS nomeCategoria, c.descricao AS descricaoCategoria
                FROM produto p JOIN categoria c ON ( p.idCategoria = c.id )"; 

        $produtos = $this->carregarObjetosDaClasse( $sql, Produto::class, [], "Erro ao carregar produtos." );
        foreach ($produtos as $produto) {
            $categoria = new Categoria( $produto->idCategoria, $produto->nomeCategoria, $produto->descricaoCategoria );
            $produto->setCategoria($categoria);
            $produto->setUrls( json_decode( $produto->arrayUrlImg ) );
            $produto->setCores( json_decode( $produto->arrayCores ) );
        }
        return $produtos;
    }


    /** @inheritDoc */
    public function inserir( Produto $produto ): int {
        $sql = "INSERT INTO produto ( idCategoria, nome, arrayCores, arrayUrlImg, preco, descricao, dataCadastro, peso ) 
                       VALUES ( :categoria, :nome, :cores, :urls, :preco, :descricao, :dataCadastro, :peso )";
        $arrayProduto = $produto->toArray();
        unset( $arrayProduto["id"] );
        $arrayProduto["categoria"] = $arrayProduto["categoria"]->id;
        // $arrayProduto["urls"] = array_map( fn( $img ) => salvarImg( $img, "../api/imagens/produtos/" ), $arrayProduto["urls"] );
        $arrayProduto["urls"] = json_encode( $arrayProduto["urls"], JSON_UNESCAPED_SLASHES );
        $arrayProduto["cores"] = json_encode( $arrayProduto["cores"] );
        $this->executar( $sql, $arrayProduto, "Erro ao inserir produto." );
        return $this->ultimoIdGerado();
    }


    /** @inheritDoc */
    public function alterar( Produto $produto ): bool {
        $sql = "UPDATE produto SET idCategoria = :categoria, nome = :nome, arrayCores = :cores, arrayUrlImg = :urls, 
                       preco = :preco, descricao = :descricao, dataCadastro = :dataCadastro, peso = :peso WHERE id = :id";
        $arrayProduto = $produto->toArray();
        $arrayProduto["categoria"] = $arrayProduto["categoria"]->id;
        // $arrayProduto["urls"] = array_map( fn( $img ) => salvarImg( $img, "../api/imagens/produtos/" ), $arrayProduto["urls"] );
        $arrayProduto["urls"] = json_encode( $arrayProduto["urls"], JSON_UNESCAPED_SLASHES );
        $arrayProduto["cores"] = json_encode( $arrayProduto["cores"] );
        $ps = $this->executar( $sql, $arrayProduto, "Erro ao alterar produto." );
        return $ps->rowCount() > 0;
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): bool {
        if( $this->existeComId( $id ) ) {
            $produto = $this->obterPeloId( $id );
            array_map( fn( $url ) => excluirImg( $url ), $produto->urls );
        }   
        return $this->removerRegistroComId( $id, "produto", "Erro ao excluir produto." );
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): ?Produto {
        $sql = "SELECT p.id, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                       c.id AS idCategoria, c.nome AS nomeCategoria, c.descricao AS descricaoCategoria
                FROM produto p
                JOIN categoria c ON ( p.idCategoria = c.id )
                WHERE p.id = ?"; // LIMIT ?
        $produto = $this->primeiroObjetoDaClasse( $sql, Produto::class, [ $id ], "Erro ao carregar produto." );
        $categoria = new Categoria( $produto->idCategoria, $produto->nomeCategoria, $produto->descricaoCategoria );
        $produto->setCategoria( $categoria );
        $produto->setUrls( json_decode( $produto->arrayUrlImg ) );
        $produto->setCores( json_decode( $produto->arrayCores ) );
        return $produto;
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        $sql = "SELECT id FROM produto WHERE id = ?";
        $produto = $this->primeiroObjetoDaClasse( $sql, Produto::class, [ $id ], "Erro ao verificar produto." );
        return $produto !== null;
    }


    /** @inheritDoc */
    public function rankProdutosMaisVendidos(): array {
        $sql = "SELECT p.id, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                       c.id AS idCategoria, c.nome AS nomeCategoria, c.descricao AS descricaoCategoria
                FROM venda_produto_tamanho vpt
                JOIN produto p ON ( vpt.idProduto = p.id )
                JOIN categoria c ON ( p.idCategoria = c.id )
                GROUP BY p.id, c.id ORDER BY SUM( vpt.qtd ) DESC"; // LIMIT ?

        $produtos = $this->carregarObjetosDaClasse( $sql, Produto::class, [], "Erro ao carregar produtos." );
        foreach ($produtos as $produto) {
            $categoria = new Categoria( $produto->idCategoria, $produto->nomeCategoria, $produto->descricaoCategoria );
            $produto->setCategoria($categoria);
            $produto->setUrls( json_decode( $produto->arrayUrlImg ) );
            $produto->setCores( json_decode( $produto->arrayCores ) );
        }
        return $produtos;
    }
}
?>