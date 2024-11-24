<?php
declare(strict_types = 1);

class GestorTransacao {
    private PDO $conexao;

    public function __construct( PDO $conexao ) {
        $this->conexao = $conexao;
    }

    public function iniciar(): void {
        $this->conexao->beginTransaction();
    }

    public function confirmar(): void {
        $this->conexao->commit();
    }

    public function reverter(): void {
        $this->conexao->rollBack();
    }
}
?>