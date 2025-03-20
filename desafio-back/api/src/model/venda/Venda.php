<?php
declare(strict_types = 1);


class Venda extends Validavel implements JsonSerializable {
    public float $valorFrete = 10.00;
    public int $percentualDesconto = 0;
    

    public function __construct(
        public int $id = 0,
        public ?Cliente $cliente = null,
        public ?Endereco $endereco = null,
        public ?FormaPagamento $formaPagamento = null,
        public float $valorTotal = 0.0
    ){}


    public function setPercentualDesconto():void {
        if( $this->formaPagamento->value === "Pix" )
            $this->percentualDesconto = 10;
    }


    public function calcularValorTotal( array $dadosProdutos, GestorProduto $gestorProduto ): float {
        $valorTotal = 0;
        foreach( $dadosProdutos as $dadosProduto ) {
            $produto = $gestorProduto->produtoComId( $dadosProduto["id"] );
            foreach( $dadosProduto["tamanhos"] as $tamanho ) {
                $valorTotal += ( $produto->preco * $tamanho["qtd"] );
            }
        }
        return $valorTotal;
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


    public function toArray(): array {
        return get_object_vars( $this );
    }

    public function jsonSerialize(): array {  
        return [
            "id" => $this->id,
            "cliente" => $this->cliente,
            "endereco" => $this->endereco,
            "formaPagamento" => $this->formaPagamento,
            "valorTotal" => $this->valorTotal,
            "valorFrete" => $this->valorFrete,
            "percentualDesconto" => $this->percentualDesconto
        ];
    }
}

?>