<?php
declare( strict_types=1 );


interface TamanhoPersistivel {
    /**
     * Retorna todos os tamanhos.
     *
     * @return array<Tamanho>
     * @throws RuntimeException
     */
    public function obterTodos(): array;

    /**
     * Obtem um tamanho pelo id.
     *
     * @param int $id
     * @return ?Tamanho
     * @throws RuntimeException
     */
    public function obterPeloId( int $id ): ?Tamanho;

    /**
     * Verifica a existência de um tamanho pelo id.
     *
     * @param int $id
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $id ): bool;
}
