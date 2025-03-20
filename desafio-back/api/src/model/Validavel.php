<?php

declare(strict_types=1);

abstract class Validavel {
    protected array $problemas = [];

    /**
     * Valida os atributos do objeto e adiciona os problemas no array $problemas.
     */
    abstract public function validar(): void; 

    /**
     * Retorna o array de problemas do objeto.
     */
    public function getProblemas(): array {
        return $this->problemas;
    }
}

?>