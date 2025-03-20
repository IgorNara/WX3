<?php
declare(strict_types = 1);


class Tamanho extends Validavel implements JsonSerializable {

    public function __construct(
        public int $id = 0,
        public ?CampoUnicoTamanho $sigla = null
    ){}


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