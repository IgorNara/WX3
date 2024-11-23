<?php
declare( strict_types=1 );


interface VendaPersistivel {
    /**
     * Adiciona uma venda.
     *
     * @param Venda $venda
     * @return int id gerado
     * @throws RuntimeException
     */
    public function inserir( Venda $venda ): int;

    /**
     * Obtem uma venda pelo id.
     *
     * @param int $id
     * @return Venda
     * @throws RuntimeException
     */
    public function obterPeloId( int $id ): Venda;

    /**
     * Verifica a existência de uma venda pelo id.
     *
     * @param int $id
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $id ): bool;
}
