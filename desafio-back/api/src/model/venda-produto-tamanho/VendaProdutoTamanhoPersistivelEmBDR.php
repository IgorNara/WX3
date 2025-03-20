<?php
declare( strict_types=1 );


class VendaProdutoTamanhoPersistivelEmBDR extends PersistivelEmBDR implements VendaProdutoTamanhoPersistivel {

    /** @inheritDoc */
    public function obterTodos(): array {
        $sql = "SELECT vpt.qtd, vpt.precoVenda,
                       vpt.idVenda, v.valorTotal, v.valorFrete, v.percentualDesconto, v.formaPagamento,
                       vpt.idProduto, p.nome AS nomeProduto, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao AS descricaoProduto, p.dataCadastro, p.peso,
                       vpt.idTamanho, t.sigla,
                       v.idCliente, cl.nomeCompleto, cl.cpf, cl.dataNascimento, 
                       v.idEndereco, e.logradouro, e.cidade, e.bairro, e.numero, e.cep, e.complemento,
                       p.idCategoria, ca.nome AS nomeCategoria, ca.descricao AS descricaoCategoria 
                FROM venda_produto_tamanho vpt 
                JOIN venda v ON ( vpt.idVenda = v.id )
                JOIN cliente cl ON ( v.idCliente = cl.id )
                JOIN endereco e ON ( v.idEndereco = e.id )
                JOIN produto p ON ( vpt.idProduto = p.id )
                JOIN categoria ca ON ( p.idCategoria = ca.id )
                JOIN tamanho t ON ( vpt.idTamanho = t.id )";
        $vendasProdutosTamanhos = $this->carregarObjetosDaClasse( $sql, VendaProdutoTamanho::class, [], "Erro ao carregar informações da venda!" );
        foreach( $vendasProdutosTamanhos as $vpt ) {
            $cliente = new Cliente( $vpt->idCliente, $vpt->nomeCompleto, $vpt->cpf, $vpt->dataNascimento );
            $endereco = new Endereco( $vpt->idEndereco, $vpt->logradouro, $vpt->cidade, $vpt->bairro, $vpt->cep, $vpt->numero, $vpt->complemento );
            $venda = new Venda( $vpt->idVenda, $cliente, $endereco, FormaPagamento::from( $vpt->formaPagamento ), (float) $vpt->valorTotal );
            $categoria = new Categoria( $vpt->idCategoria, $vpt->nomeCategoria, $vpt->descricaoCategoria );
            $produto = new Produto( $vpt->idProduto, $categoria, $vpt->nomeProduto, json_decode( $vpt->arrayCores ), json_decode( $vpt->arrayUrlImg ), (float) $vpt->preco, $vpt->descricaoProduto, $vpt->dataCadastro, (float) $vpt->peso );
            $tamanho = new Tamanho( $vpt->idTamanho, CampoUnicoTamanho::from( $vpt->sigla ) );
            $vpt->venda = $venda;
            $vpt->produto = $produto;
            $vpt->tamanho = $tamanho;
            $vpt->setPrecoVenda();
        }
        return $vendasProdutosTamanhos;
    }


    /** @inheritDoc */
    public function inserir( VendaProdutoTamanho $vendaProdutoTamanho ): int {
        $sql = "INSERT INTO venda_produto_tamanho ( idVenda, idProduto, idTamanho, qtd, precoVenda ) 
                VALUES ( :venda, :produto, :tamanho, :qtd, :precoVenda)";
        $arrayVPT = $vendaProdutoTamanho->toArray();
        $arrayVPT["venda"] = $vendaProdutoTamanho->venda->id;
        $arrayVPT["produto"] = $vendaProdutoTamanho->produto->id;
        $arrayVPT["tamanho"] = $vendaProdutoTamanho->tamanho->id;
        $this->executar( $sql, $arrayVPT, "Erro ao inserir relação entre venda, produto e tamanho!" );
        return $this->ultimoIdGerado();
    }


    /** @inheritDoc */
    public function obterPeloId( int $idVenda ): array {
        $sql = "SELECT vpt.qtd, vpt.precoVenda,
                       vpt.idVenda, v.valorTotal, v.valorFrete, v.percentualDesconto, v.formaPagamento,
                       vpt.idProduto, p.nome AS nomeProduto, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao AS descricaoProduto, p.dataCadastro, p.peso,
                       vpt.idTamanho, t.sigla,
                       v.idCliente, cl.nomeCompleto, cl.cpf, cl.dataNascimento, 
                       v.idEndereco, e.logradouro, e.cidade, e.bairro, e.numero, e.cep, e.complemento,
                       p.idCategoria, ca.nome AS nomeCategoria, ca.descricao AS descricaoCategoria 
                FROM venda_produto_tamanho vpt 
                JOIN venda v ON ( vpt.idVenda = v.id )
                JOIN cliente cl ON ( v.idCliente = cl.id )
                JOIN endereco e ON ( v.idEndereco = e.id )
                JOIN produto p ON ( vpt.idProduto = p.id )
                JOIN categoria ca ON ( p.idCategoria = ca.id )
                JOIN tamanho t ON ( vpt.idTamanho = t.id )
                WHERE vpt.idVenda = ?";
        $tamanhosProdutosVenda = $this->carregarObjetosDaClasse( $sql, VendaProdutoTamanho::class, [ $idVenda ], "Erro ao buscar informações da venda!" );
        foreach( $tamanhosProdutosVenda as $vpt ) {
            $cliente = new Cliente( $vpt->idCliente, $vpt->nomeCompleto, $vpt->cpf, $vpt->dataNascimento );
            $endereco = new Endereco( $vpt->idEndereco, $vpt->logradouro, $vpt->cidade, $vpt->bairro, $vpt->cep, $vpt->numero, $vpt->complemento );
            $venda = new Venda( $vpt->idVenda, $cliente, $endereco, FormaPagamento::from( $vpt->formaPagamento ), (float) $vpt->valorTotal );
            $categoria = new Categoria( $vpt->idCategoria, $vpt->nomeCategoria, $vpt->descricaoCategoria );
            $produto = new Produto( $vpt->idProduto, $categoria, $vpt->nomeProduto, json_decode( $vpt->arrayCores ), json_decode( $vpt->arrayUrlImg ), (float) $vpt->preco, $vpt->descricaoProduto, $vpt->dataCadastro, (float) $vpt->peso );
            $tamanho = new Tamanho( $vpt->idTamanho, CampoUnicoTamanho::from( $vpt->sigla ) );
            $vpt->venda = $venda;
            $vpt->produto = $produto;
            $vpt->tamanho = $tamanho;
            $vpt->setPrecoVenda();
        }

        return $tamanhosProdutosVenda;
    }


    /** @inheritDoc */
    public function existeComId( int $idVenda ): bool {
        $sql = "SELECT idVenda FROM venda_produto_tamanho WHERE idVenda = ?";
        $vpt = $this->primeiroObjetoDaClasse( $sql, VendaProdutoTamanho::class, [ $idVenda ], "Erro ao verificar relação entre venda, produto e tamanho!" );
        return $vpt !== null;
    }
}
?>