<?php
declare(strict_types = 1);


class VendaProdutoTamanho extends Validavel implements JsonSerializable {
    
    public function __construct(
        private ?Venda $venda = null,
        private ?Produto $produto = null,
        private ?Tamanho $tamanho = null,
        private int $qtd = 0,
        private float $precoVenda = 0
    ){}

    public function __get( string $atributo ): mixed {
        return $this->$atributo ?? throw new RuntimeException( "Erro ao buscar atributo", 500 );
    }

    public function setVenda( Venda $venda ): void {
        $this->venda = $venda;
    }

    public function setProduto( Produto $produto ): void {
        $this->produto = $produto;
    }

    public function setTamanho( Tamanho $tamanho ): void {
        $this->tamanho = $tamanho;
    } 

    public function setPrecoVenda(): void {
        $arrayProduto = $this->produto->toArray();
        $this->precoVenda = ( $arrayProduto["preco"] * $this->qtd );
    }


    public function validar(): void {
        // Quantidade
        if( $this->qtd <= 0 )
            $this->problemas[] = "A quantidade do produto vendido deve ser maior que zero.";

        // Preco Venda
        if( $this->precoVenda <= 0 )
            $this->problemas[] = "O valor de venda do produto vendido deve ser maior que zero.";
    }


    public function toArray(): array {
        return [
            "venda" => $this->venda,
            "produto" => $this->produto,
            "tamanho" => $this->tamanho,
            "qtd" => $this->qtd,
            "precoVenda" => $this->precoVenda
        ];
    }


    public function jsonSerialize(): array {
        return $this->toArray();
    }
}
?>