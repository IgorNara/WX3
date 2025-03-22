<?php

declare(strict_types=1);

interface ClienteEnderecoPersistivel {

    /**
     * Retorna todos os endereços de um cliente.
     * 
     * @param int $idCliente
     * @return array<ClienteEndereco>
     * @throws RuntimeException
     */
    public function obterPeloId( int $idCliente ) : array;

    /**
     * Adiciona um endereço ao cliente.
     *
     * @param ClienteEndereco $clienteEndereco
     * @return int
     * @throws RuntimeException
     */
    public function inserir( ClienteEndereco $clienteEndereco ): int;

    /**
     * Verifica se existe a relação entre o cliente e algum endereço
     * 
     * @param int $idCliente
     * @return bool
     * @throws RuntimeException
     */
    public function existeComId( int $idCliente ): bool;
}

?>