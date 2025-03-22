<?php

declare(strict_types = 1);

class TamanhoProduto extends Validavel implements JsonSerializable {

    public function __construct(
        public ?Produto $produto = null,
        public ?Tamanho $tamanho = null,
        public int $qtd = 0,
    ){}


    public function validar(): void {
        // Quantidade
        if( $this->qtd < 0 )
            $this->problemas[] = "A quantidade de um tamanho deve ser maior ou igual a zero.";
    }


    public function toArray(): array {
        $array = get_object_vars( $this );
        unset( $array["problemas"] );
        return $array;
    }

    
    public function jsonSerialize(): array {
        return [
            "produto" => $this->produto,
            "tamanho" => $this->tamanho,
            "qtd" => $this->qtd
        ];
    }
}

?>