<?php
declare( strict_types=1 );


interface ClientePersistivel {
    /**
     * Retorna todos os clientes.
     *
     * @return array<Cliente>
     * @throws RuntimeException
     */
    public function obterTodos(): array;

    /**
     * Adiciona um cliente.
     *
     * @param Cliente $cliente
     * @return int id gerado
     * @throws RuntimeException
     */
    public function inserir( Cliente $cliente ): int;

    /**
     * Remove um cliente pelo id.
     *
     * @param int $id
     * @return bool
     * @throws RuntimeException
     */
    public function excluirPeloId( int $id ): bool;

    /**
     * Obtem um cliente pelo id.
     *
     * @param int $id
     * @return Cliente
     * @throws RuntimeException
     */
    public function obterPeloId( int $id ): Cliente;

    /**
     * Atualiza um cliente.
     *
     * @param Cliente $cliente
     * @return int linhas afetadas
     * @throws RuntimeException
     */
    public function alterar( Cliente $cliente ): int;

    /**
     * Verifica a existência de um cliente pelo id.
     *
     * @param int $id
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $id ): bool;
}
