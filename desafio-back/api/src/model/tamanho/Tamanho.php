<?php
declare(strict_types = 1);


class Tamanho extends Validavel implements JsonSerializable {

    public function __construct(
        private int $id = 0,
        private ?CampoUnicoTamanho $sigla = null
    ){}

    public function __get( string $atributo ): mixed {
        return $this->$atributo ?? throw new RuntimeException( "Erro ao buscar atributo", 500 );
    }


    public function setSigla( CampoUnicoTamanho $sigla ): void {
        $this->sigla = $sigla;
    }


    public function validar(): void {
        // sigla
        if( ! $this->sigla->name )
            $this->problemas[] = "Fornecer o tamanho é obrigatório.";
    }


    public function jsonSerialize(): array {
        return [
            "id" => $this->id,
            "sigla" => $this->sigla->value
        ];
    }
}

?>