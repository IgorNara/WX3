<?php
declare(strict_types = 1);


class Endereco extends Validavel implements JsonSerializable {

    public function __construct(
        private int $id = 0,
        private string $logradouro = "",
        private string $cidade = "",
        private string $bairro = "",
        private string $cep = "",
        private ?int $numero = null,
        private ?string $complemento = null
    ){
        $this->setCep( $cep );
    }


    public function __get( string $atributo ): mixed {
        return $this->$atributo ?? throw new RuntimeException( "Erro ao buscar atributo", 500 );
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


    public function toArray(): array {
        return [
            "id" => $this->id,
            "logradouro" => $this->logradouro,
            "cidade" => $this->cidade,
            "bairro" => $this->bairro,
            "cep" => $this->cep,
            "numero" => $this->numero,
            "complemento" => $this->complemento
        ];
    }


    public function jsonSerialize(): array {
        return $this->toArray();
    }
}

?>