<?php
declare(strict_types = 1);


class VendaProdutoTamanho implements JsonSerializable {
    private array $problemas = [];
    
    public function __construct(
        public ?Venda $venda = null,
        public ?Produto $produto = null,
        public ?Tamanho $tamanho = null,
        public int $qtd = 0,
        public float $precoVenda = 0
    ){}

    public function setPrecoVenda(): void {
        $this->precoVenda = ( $this->produto->preco * $this->qtd );
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


    public function jsonSerialize(): array {
        return [
            "venda" => $this->venda,
            "produto" => $this->produto,
            "tamanho" => $this->tamanho,
            "qtd" => $this->qtd,
            "precoVenda" => $this->precoVenda
        ];
    }
}
?>