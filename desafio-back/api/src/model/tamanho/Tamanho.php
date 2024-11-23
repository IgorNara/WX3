<?php
declare(strict_types = 1);


class Tamanho {
    private array $problemas = [];

    public function __construct(
        public int $id = 0,
        public ?CampoUnicoTamanho $sigla = null
    ){}


    public function getProblemas(): array {
        return $this->problemas;
    }


    public function validar(): void {
        // sigla
        if( ! $this->sigla->name )
            $this->problemas[] = "Fornecer o tamanho é obrigatório.";
    }
}

?>