<?php
declare(strict_types = 1);


class Venda {
    private array $problemas = [];
    public float $valorFrete = 10.00;
    public int $percentualDesconto = 0;
    

    public function __construct(
        public int $id = 0,
        public Cliente $cliente,
        public Endereco $endereco,
        public ?FormaPagamento $formaPagamento = null,
        public float $valorTotal = 0.0
    ){
        if( $this->formaPagamento->value === "Pix" )
            $this->percentualDesconto = 10;
    }


    public function calcularValorTotal( array $dadosProdutos, ProdutoPersistivelEmBDR $produtoPersistivel ): float {
        $valorTotal = 0;
        foreach( $dadosProdutos as $dadosProduto ) {
            $produto = $produtoPersistivel->obterPeloId( $dadosProduto["id"] );
            foreach( $dadosProduto["tamanhos"] as $tamanho ) {
                $valorTotal += ( $produto->preco * $tamanho["qtd"] );
            }
        }
        return $valorTotal;
    }


    public function getProblemas(): array {
        return $this->problemas;
    }


    public function validar(): void {
        // Valor Total
        if( $this->valorTotal <= 0 )
            $this->problemas[] = "O valor total deve ser maior que zero.";

        // Valor Frete
        if( $this->valorFrete < 0 )
            $this->problemas[] = "O frete deve ser maior ou igual a zero.";

        // Percentual Desconto
        if( $this->percentualDesconto < 0 || $this->percentualDesconto > 100 )
            $this->problemas[] = "O percentual de desconto deve ser entre 0% e 100%.";
        
        // Forma Pagamento
        if( !$this->formaPagamento )
            $this->problemas[] = "Fornecer a forma de pagamento é obrigatório.";
    }
}

?>