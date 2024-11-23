<?php
declare(strict_types = 1);


class VendaProdutoTamanho {
    private array $problemas = [];
    
    public function __construct(
        public Venda $venda,
        public Produto $produto,
        public Tamanho $tamanho,
        public int $qtd = 0,
        public float $precoVenda = 0
    ){
        $this->precoVenda = ( $produto->preco * $qtd );
    }


    public function getProblemas(): array {
        return $this->problemas;
    }


    public function validar(): void {
        // Quantidade
        if( $this->qtd <= 0 )
            $this->problemas[] = "A quantidade do produto vendido deve ser maior que zero.";

        // Preco Venda
        if( $this->precoVenda <= 0 )
            $this->problemas[] = "O valor de venda do produto vendido deve ser maior que zero.";
    }
}
?>