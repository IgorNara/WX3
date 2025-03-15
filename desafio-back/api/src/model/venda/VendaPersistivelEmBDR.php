<?php
declare( strict_types=1 );


class VendaPersistivelEmBDR extends PersistivelEmBDR implements VendaPersistivel {

    /** @inheritDoc */
    public function inserir( Venda $venda ): int {
        $sql = "INSERT INTO venda ( idCliente, idEndereco, valorTotal, valorFrete, percentualDesconto, formaPagamento ) 
                       VALUES ( :cliente, :endereco, :valorTotal, :valorFrete, :percentualDesconto, :formaPagamento )";
        $arrayVenda = $venda->jsonSerialize();
        unset( $arrayVenda["id"] );
        $arrayVenda["cliente"] = $venda->cliente->id;
        $arrayVenda["endereco"] = $venda->endereco->id;
        $arrayVenda["formaPagamento"] = $venda->formaPagamento->value;
        $this->executar( $sql, $arrayVenda, "Erro ao inserir venda!" );
        return $this->ultimoIdGerado();
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Venda {
        $sql = "SELECT v.id, v.idCliente, v.idEndereco, v.valorTotal, v.valorFrete, v.percentualDesconto, v.formaPagamento AS stringFormaPagamento,
                       c.nomeCompleto, c.cpf, c.dataNascimento,
                       e.logradouro, e.cidade, e.bairro, e.numero, e.cep, e.complemento
                FROM venda v 
                JOIN cliente c ON ( v.idCliente = c.id )
                JOIN endereco e ON ( v.idEndereco = e.id ) WHERE v.id = ?";

        $venda = $this->primeiroObjetoDaClasse( $sql, Venda::class, [ $id ], "Erro ao buscar venda!" );
        $venda->formaPagamento = FormaPagamento::from( $venda->stringFormaPagamento );
        $cliente = new Cliente( $venda->idCliente, $venda->nomeCompleto, $venda->cpf, $venda->dataNascimento );
        $endereco = new Endereco( $venda->idEndereco, $venda->logradouro, $venda->cidade, $venda->bairro, $venda->cep, (int) $venda->numero, $venda->complemento );
        $venda = new Venda( $venda->id, $cliente, $endereco, $venda->formaPagamento, $venda->valorTotal );
        $venda->setPercentualDesconto();
        return $venda;
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        $sql = "SELECT id FROM venda WHERE id = ?";
        $venda = $this->primeiroObjetoDaClasse( $sql, Venda::class, [ $id ], "Erro ao verificar venda!" );
        return $venda !== null;
    }
}
?>