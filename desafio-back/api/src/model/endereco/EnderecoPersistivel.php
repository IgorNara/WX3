<?php
declare( strict_types=1 );


interface EnderecoPersistivel {
    /**
     * Retorna todos os endereços.
     *
     * @return array<Endereco>
     * @throws RuntimeException
     */
    public function obterTodos(): array;

    /**
     * Adiciona um endereço.
     *
     * @param Endereco $endereco
     * @return int id gerado
     * @throws RuntimeException
     */
    public function inserir( Endereco $endereco ): int;

    /**
     * Remove um endereço pelo id.
     *
     * @param int $id
     * @return bool 
     * @throws RuntimeException
     */
    public function excluirPeloId( int $id ): bool;

    /**
     * Obtem um endereço pelo id.
     *
     * @param int $id
     * @return Endereco
     * @throws RuntimeException
     */
    public function obterPeloId( int $id ): Endereco;

    /**
     * Atualiza um endereço.
     *
     * @param Endereco $endereco
     * @return int linhas afetadas
     * @throws RuntimeException
     */
    public function alterar( Endereco $endereco ): int;

    /**
     * Verifica a existência de um endereço pelo id.
     *
     * @param int $id
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $id ): bool;
}
