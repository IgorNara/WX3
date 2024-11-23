<?php
declare(strict_types = 1);


class Endereco {
    private array $problemas = [];

    public function __construct(
        public int $id = 0,
        public string $logradouro = "",
        public string $cidade = "",
        public string $bairro = "",
        public string $cep = "",
        public ?int $numero = null,
        public ?string $complemento = null
    ){
        $this->setCep( $cep );
    }


    public function getProblemas(): array {
        return $this->problemas;
    }


    public function setCep( $cep ): void {
        $this->cep = str_replace( "-", "", $cep );
    }


    public function validar(): void {
        // Logradouro
        if( strlen( $this->logradouro ) <= 5 || strlen( $this->logradouro ) > 30 )
            $this->problemas[] = "O logradouro deve ter no mínimo 6 e no máximo 30 caracteres.";

        // Cidade
        if( strlen( $this->cidade ) <= 2 || strlen( $this->cidade ) > 30 )
            $this->problemas[] = "A cidade deve ter no mínimo 3 e no máximo 30 caracteres.";

        // Bairro
        if( strlen( $this->bairro ) <= 2 || strlen( $this->bairro ) > 30 )
            $this->problemas[] = "O bairro deve ter no mínimo 3 e no máximo 30 caracteres.";

        // CEP
        if( strlen( $this->cep ) !== 8 )
            $this->problemas[] = "O CEP deve ter exatamente 8 caracteres numéricos.";

        // Número
        if( $this->numero && $this->numero < 0 )
            $this->problemas[] = "O número de uma casa deve ser positivo.";

        // Complemento
        if( $this->complemento && strlen( $this->logradouro ) > 60 )
            $this->problemas[] = "O complemento deve ter no máximo 60 caracteres.";
    }
}

?>