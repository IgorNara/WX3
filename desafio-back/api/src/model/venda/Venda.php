<?php
declare(strict_types = 1);


class Venda extends Validavel implements JsonSerializable {
    private float $valorFrete = 10.00;
    

    public function __construct(
        private int $id = 0,
        private ?Cliente $cliente = null,
        private ?Endereco $endereco = null,
        private ?FormaPagamento $formaPagamento = null,
        private float $valorTotal = 0.0,
        private int $percentualDesconto = 0
    ){}


    public function __get( string $atributo ): mixed {
        return $this->$atributo ?? throw new RuntimeException( "Erro ao buscar atributo", 500 );
    }


    public function setPercentualDesconto():void {
        if( $this->formaPagamento->value === "Pix" )
            $this->percentualDesconto = 10;
        else 
            $this->percentualDesconto = 0;
    }


    public function calcularValorTotal( array $dadosProdutos, GestorProduto $gestorProduto ): void {
        $valorTotal = 0;
        foreach( $dadosProdutos as $dadosProduto ) {
            $produto = $gestorProduto->produtoComId( $dadosProduto["id"] );
            foreach( $dadosProduto["tamanhos"] as $tamanho ) {
                $valorTotal += ( $produto->preco * $tamanho["qtd"] );
            }
        }
        $this->valorTotal = ($valorTotal - $valorTotal * ( $this->percentualDesconto/100 ));
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

    public function jsonSerialize(): array {  
        return $this->toArray();
    }
}

?>