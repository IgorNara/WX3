<?php

declare(strict_types = 1);

class TamanhoProduto extends Validavel implements JsonSerializable {

    public function __construct(
        private ?Produto $produto = null,
        private ?Tamanho $tamanho = null,
        private int $qtd = 0,
    ){}
    
    public function __get( string $atributo ): mixed {
        return $this->$atributo ?? throw new RuntimeException( "Erro ao buscar atributo", 500 );
    }
    

    public function setProduto( Produto $produto ): void {
        $this->produto = $produto;
    }


    public function setTamanho( Tamanho $tamanho ): void {
        $this->tamanho = $tamanho;
    }


    public function setQtd( int $qtd ): void {
        $this->qtd = $qtd;
    }


    public function validar(): void {
        // Quantidade
        if( $this->qtd < 0 )
            $this->problemas[] = "A quantidade de um tamanho deve ser maior ou igual a zero.";
    }


    public function toArray(): array {
        return [
            "produto" => $this->produto,
            "tamanho" => $this->tamanho,
            "qtd" => $this->qtd
        ];
    }

    
    public function jsonSerialize(): array {
        return $this->toArray();
    }
}

?>