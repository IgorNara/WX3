<?php
declare( strict_types=1 );


class VendaProdutoTamanhoPersistivelEmBDR implements VendaProdutoTamanhoPersistivel {
    private PDO $conexao;

    public function __construct ( PDO $conexao ) {
        $this->conexao = $conexao;
    }


    /** @inheritDoc */
    public function obterTodos(): array {
        $vendasProdutosTamanhos = [];
        try {
            $sql = "SELECT vpt.qtd, vpt.precoVenda,
                           vpt.idVenda, v.valorTotal, v.valorFrete, v.percentualDesconto, v.formaPagamento,
                           vpt.idProduto, p.nome AS produto, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao AS descricaoProduto, p.dataCadastro, p.peso,
                           vpt.idTamanho, t.sigla,
                           v.idCliente, cl.nomeCompleto, cl.cpf, cl.dataNascimento, 
                           v.idEndereco, e.logradouro, e.cidade, e.bairro, e.numero, e.cep, e.complemento,
                           p.idCategoria, ca.nome AS categoria, ca.descricao AS descricaoCategoria 
                    FROM venda_produto_tamanho vpt 
                    JOIN venda v ON ( vpt.idVenda = v.id )
                    JOIN cliente cl ON ( v.idCliente = cl.id )
                    JOIN endereco e ON ( v.idEndereco = e.id )
                    JOIN produto p ON ( vpt.idProduto = p.id )
                    JOIN categoria ca ON ( p.idCategoria = ca.id )
                    JOIN tamanho t ON ( vpt.idTamanho = t.id )";

            $ps = $this->conexao->prepare( $sql );
            $ps->execute();

            $resposta = $ps->fetchAll();
            foreach( $resposta as $vpt ) {
                $cliente = new Cliente( $vpt["idCliente"], $vpt["nomeCompleto"], $vpt["cpf"], $vpt["dataNascimento"] );

                $endereco = new Endereco( $vpt["idEndereco"], $vpt["logradouro"], $vpt["cidade"], $vpt["bairro"], $vpt["cep"], $vpt["numero"], $vpt["complemento"] );

                $venda = new Venda( $vpt["idVenda"], $cliente, $endereco, FormaPagamentoDesconto::from( $vpt["formaPagamento"] ), $vpt["valorTotal"] );

                $categoria = new Categoria( $vpt["idCategoria"], $vpt["categoria"], $vpt["descricaoCategoria"] );

                $produto = new Produto( $vpt["idProduto"], $categoria, $vpt["produto"], $vpt["arrayCores"], $vpt["arrayUrlImg"], $vpt["preco"], $vpt["descricaoProduto"], $vpt["dataCadastro"], $vpt["peso"] );

                $tamanho = new Tamanho( $vpt["idTamanho"], CampoUnicoTamanho::from( $vpt["sigla"] ) );

                $vendasProdutosTamanhos[] = new VendaProdutoTamanho( $venda, $produto, $tamanho, $vpt["qtd"], $vpt["precoVenda"] );
            }
            return $vendasProdutosTamanhos;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao listar informações das vendas - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function inserir( VendaProdutoTamanho $vendaProdutoTamanho ): int {
        try {
            $sql = "INSERT INTO venda_produto_tamanho ( idVenda, idProduto, idTamanho, qtd, precoVenda ) VALUES ( ?, ?, ?, ?, ?)";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $vendaProdutoTamanho->venda->id );
            $ps->bindParam( 2, $vendaProdutoTamanho->produto->id );
            $ps->bindParam( 3, $vendaProdutoTamanho->tamanho->id );
            $ps->bindParam( 4, $vendaProdutoTamanho->qtd );
            $ps->bindParam( 5, $vendaProdutoTamanho->precoVenda );
            $ps->execute();

            return intval( $this->conexao->lastInsertId() );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao inserir informações da venda - ".$erro->getMessage() );
        }  
    }


    /** @inheritDoc */
    public function alterar( VendaProdutoTamanho $vendaProdutoTamanho ): int {
        try {
            $sql = "UPDATE venda_produto_tamanho SET qtd = ?, precoVenda = ? WHERE idVenda = ? AND idProduto = ? AND idTamanho = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $vendaProdutoTamanho->qtd );
            $ps->bindParam( 2, $vendaProdutoTamanho->precoVenda );
            $ps->bindParam( 3, $vendaProdutoTamanho->venda->id );
            $ps->bindParam( 4, $vendaProdutoTamanho->produto->id );
            $ps->bindParam( 5, $vendaProdutoTamanho->tamanho->id );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao alterar informações da venda - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function excluirPeloId( int $idVenda, int $idProduto, int $idTamanho ): int {
        try {
            $sql = "DELETE FROM venda_produto_tamanho WHERE idVenda = ? AND idProduto = ? AND idTamanho = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $idVenda );
            $ps->bindParam( 2, $idProduto );
            $ps->bindParam( 3, $idTamanho );
            $ps->execute();

            return $ps->rowCount();
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao excluir informações da venda - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function obterPeloId( int $idVenda, int $idProduto, int $idTamanho ): VendaProdutoTamanho {
        try {
            $sql = "SELECT vpt.qtd, vpt.precoVenda,
                           v.valorTotal, v.valorFrete, v.percentualDesconto, v.formaPagamento,
                           p.nome AS produto, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao AS descricaoProduto, p.dataCadastro, p.peso,
                           t.sigla,
                           v.idCliente, cl.nomeCompleto, cl.cpf, cl.dataNascimento, 
                           v.idEndereco, e.logradouro, e.cidade, e.bairro, e.numero, e.cep, e.complemento,
                           p.idCategoria, ca.nome AS categoria, ca.descricao AS descricaoCategoria 
                    FROM venda_produto_tamanho vpt 
                    JOIN venda v ON ( vpt.idVenda = v.id )
                    JOIN cliente cl ON ( v.idCliente = cl.id )
                    JOIN endereco e ON ( v.idEndereco = e.id )
                    JOIN produto p ON ( vpt.idProduto = p.id )
                    JOIN categoria ca ON ( p.idCategoria = ca.id )
                    JOIN tamanho t ON ( vpt.idTamanho = t.id )
                    WHERE idVenda = ? AND idProduto = ? AND idTamanho = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $idVenda );
            $ps->bindParam( 2, $idProduto );
            $ps->bindParam( 3, $idTamanho );
            $ps->execute();

            $resposta = $ps->fetch();

            $cliente = new Cliente( $resposta["idCliente"], $resposta["nomeCompleto"], $resposta["cpf"], $resposta["dataNascimento"] );

            $endereco = new Endereco( $resposta["idEndereco"], $resposta["logradouro"], $resposta["cidade"], $resposta["bairro"], $resposta["cep"], $resposta["numero"], $resposta["complemento"] );

            $venda = new Venda( $idVenda, $cliente, $endereco, FormaPagamentoDesconto::from( $resposta["formaPagamento"] ), $resposta["valorTotal"] );

            $categoria = new Categoria( $resposta["idCategoria"], $resposta["categoria"], $resposta["descricaoCategoria"] );

            $produto = new Produto( $idProduto, $categoria, $resposta["produto"], $resposta["arrayCores"], $resposta["arrayUrlImg"], $resposta["preco"], $resposta["descricaoProduto"], $resposta["dataCadastro"], $resposta["peso"] );

            $tamanho = new Tamanho( $idTamanho, CampoUnicoTamanho::from( $resposta["sigla"] ) );

            return new VendaProdutoTamanho( $venda, $produto, $tamanho, $resposta["qtd"], $resposta["precoVenda"] );
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar informações da venda - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function obterPeloIdVenda( int $idVenda ): array {
        $tamanhosProdutosVenda = [];
        try {
            $sql = "SELECT vpt.qtd, vpt.precoVenda,
                           v.valorTotal, v.valorFrete, v.percentualDesconto, v.formaPagamento,
                           vpt.idProduto, p.nome AS produto, p.arrayCores, p.arrayUrlImg, p.preco, p.descricao AS descricaoProduto, p.dataCadastro, p.peso,
                           vpt.idTamanho, t.sigla,
                           v.idCliente, cl.nomeCompleto, cl.cpf, cl.dataNascimento, 
                           v.idEndereco, e.logradouro, e.cidade, e.bairro, e.numero, e.cep, e.complemento,
                           p.idCategoria, ca.nome AS categoria, ca.descricao AS descricaoCategoria 
                    FROM venda_produto_tamanho vpt 
                    JOIN venda v ON ( vpt.idVenda = v.id )
                    JOIN cliente cl ON ( v.idCliente = cl.id )
                    JOIN endereco e ON ( v.idEndereco = e.id )
                    JOIN produto p ON ( vpt.idProduto = p.id )
                    JOIN categoria ca ON ( p.idCategoria = ca.id )
                    JOIN tamanho t ON ( vpt.idTamanho = t.id )
                    WHERE idVenda = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $idVenda );
            $ps->execute();

            $resposta = $ps->fetchAll();
            foreach( $resposta as $vpt ) {
                $cliente = new Cliente( $vpt["idCliente"], $vpt["nomeCompleto"], $vpt["cpf"], $vpt["dataNascimento"] );

                $endereco = new Endereco( $vpt["idEndereco"], $vpt["logradouro"], $vpt["cidade"], $vpt["bairro"], $vpt["cep"], $vpt["numero"], $vpt["complemento"] );
    
                $venda = new Venda( $idVenda, $cliente, $endereco, FormaPagamentoDesconto::from( $vpt["formaPagamento"] ), $vpt["valorTotal"] );
    
                $categoria = new Categoria( $vpt["idCategoria"], $vpt["categoria"], $vpt["descricaoCategoria"] );
    
                $produto = new Produto( $vpt["idProduto"], $categoria, $vpt["produto"], $vpt["arrayCores"], $vpt["arrayUrlImg"], $vpt["preco"], $vpt["descricaoProduto"], $vpt["dataCadastro"], $vpt["peso"] );
    
                $tamanho = new Tamanho( $vpt["idTamanho"], CampoUnicoTamanho::from( $vpt["sigla"] ) );

                $tamanhosProdutosVenda[] = new VendaProdutoTamanho( $venda, $produto, $tamanho, $vpt["qtd"], $vpt["precoVenda"] );
            }

            return $tamanhosProdutosVenda;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao buscar informações da venda - ".$erro->getMessage() );
        }
    }


    /** @inheritDoc */
    public function existeComIdVenda( int $idVenda ): bool {
        try {
            $sql = "SELECT id FROM venda_produto_tamanho WHERE idVenda = ?";

            $ps = $this->conexao->prepare( $sql );
            $ps->bindParam( 1, $idVenda );
            $ps->execute();

            return $ps->rowCount() > 0;
        }
        catch ( RuntimeException $erro ) {
            throw new RuntimeException( "Erro ao verificar informações da venda - ".$erro->getMessage() );
        }
    }
}
?>