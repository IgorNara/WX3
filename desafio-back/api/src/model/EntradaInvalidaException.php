<?php

class EntradaInvalidaException extends RuntimeException {

    protected array $problems = [];

    public function __construct( $msg, $code, $problems ){
        parent::__construct( $msg, $code );
        $this->setProblems( $problems );
    }

    /**
     * Define os problemas.
     *
     * @param array<string> $problems
     * @return EntradaInvalidaException
     */
    public function setProblems( array $problems ): self {
        $this->problems = $problems;
        return $this;
    }

    public function getProblems(): array {
        return $this->problems;
    }
}

?>