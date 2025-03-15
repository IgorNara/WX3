<?php
declare(strict_types = 1);



class TamanhoProduto implements JsonSerializable {
    private array $problemas = [];

    public function __construct(
        public ?Produto $produto = null,
        public ?Tamanho $tamanho = null,
        public int $qtd = 0,
    ){}


    public function getProblemas(): array {
        return $this->problemas;
    }


    public function validar(): void {
        // Quantidade
        if( $this->qtd < 0 )
            $this->problemas[] = "A quantidade de um tamanho deve ser maior ou igual a zero.";
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