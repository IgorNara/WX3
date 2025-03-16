<?php
declare( strict_types=1 );


class ProdutoPersistivelEmBDR extends PersistivelEmBDR implements ProdutoPersistivel {

    /** @inheritDoc */
    public function inserir( Produto $produto ): int {
        $sql = "INSERT INTO produto ( idCategoria, nome, arrayCores, arrayUrlImg, preco, descricao, dataCadastro, peso ) 
                       VALUES ( :categoria, :nome, :cores, :urls, :preco, :descricao, :dataCadastro, :peso )";
        $arrayProduto = $produto->jsonSerialize();
        unset( $arrayProduto["id"] );
        $arrayProduto["categoria"] = $arrayProduto["categoria"]->id;
        // $arrayProduto["urls"] = array_map( fn( $img ) => salvarImg( $img, "../api/imagens/produtos/" ), $arrayProduto["urls"] );
        $arrayProduto["urls"] = json_encode( $arrayProduto["urls"], JSON_UNESCAPED_SLASHES );
        $arrayProduto["cores"] = json_encode( $arrayProduto["cores"] );
        $this->executar( $sql, $arrayProduto, "Erro ao inserir produto." );
        return $this->ultimoIdGerado();
    }


    /** @inheritDoc */
    public function alterar( Produto $produto ): int {
        $sql = "UPDATE produto SET idCategoria = :categoria, nome = :nome, arrayCores = :cores, arrayUrlImg = :urls, 
                       preco = :preco, descricao = :descricao, dataCadastro = :dataCadastro, peso = :peso WHERE id = :id";
        $arrayProduto = $produto->jsonSerialize();
        $arrayProduto["categoria"] = $arrayProduto["categoria"]->id;
        // $arrayProduto["urls"] = array_map( fn( $img ) => salvarImg( $img, "../api/imagens/produtos/" ), $arrayProduto["urls"] );
        $arrayProduto["urls"] = json_encode( $arrayProduto["urls"], JSON_UNESCAPED_SLASHES );
        $arrayProduto["cores"] = json_encode( $arrayProduto["cores"] );
        $ps = $this->executar( $sql, $arrayProduto, "Erro ao alterar produto." );
        return $ps->rowCount();
    }


    /** @inheritDoc */
    public function excluirPeloId( int $id ): bool {
        $arrayProduto = ( $this->obterPeloId( $id ) )->jsonSerialize();
        array_map( fn( $url ) => excluirImg( $url ), $arrayProduto["urls"] );
        return $this->removerRegistroComId( $id, "produto", "Erro ao excluir produto." );
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Produto {
        $sql = "SELECT p.id, p.nome, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao, p.dataCadastro, p.peso,
                       c.id AS idCategoria, c.nome AS nomeCategoria, c.descricao AS descricaoCategoria
                FROM produto p
                JOIN categoria c ON ( p.idCategoria = c.id )
                WHERE p.id = ?"; // LIMIT ?
        $produto = $this->primeiroObjetoDaClasse( $sql, Produto::class, [ $id ], "Erro ao carregar produto." );
        $categoria = new Categoria( $produto->idCategoria, $produto->nomeCategoria, $produto->descricaoCategoria );
        $produto->categoria = $categoria;
        return $produto;
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        $sql = "SELECT id FROM produto WHERE id = ?";
        $produto = $this->primeiroObjetoDaClasse( $sql, Produto::class, [ $id ], "Erro ao verificar produto." );
        return $produto !== null;
    }


    /**
     * Retorna uma lista de produtos ordenada em ordem decrescente com base no seu total de vendas
     * 
     * @return array<Produto>
     * @throws RuntimeException
     */
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
            $produto->categoria = $categoria;
        }
        return $produtos;
    }
}
?>