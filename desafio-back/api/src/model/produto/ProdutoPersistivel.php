<?php
declare( strict_types=1 );


interface ProdutoPersistivel {
    /**
     * Adiciona um produto.
     *
     * @param Produto $produto
     * @return int id gerado
     * @throws RuntimeException
     */
    public function inserir( Produto $produto ): int;

    /**
     * Remove um produto pelo id.
     *
     * @param int $id
     * @return int linhas afetadas
     * @throws RuntimeException
     */
    public function excluirPeloId( int $id ): int;

    /**
     * Obtem um produto pelo id.
     *
     * @param int $id
     * @return Produto
     * @throws RuntimeException
     */
    public function obterPeloId( int $id ): Produto;

    /**
     * Atualiza um produto.
     *
     * @param Produto $produto
     * @return int linhas afetadas
     * @throws RuntimeException
     */
    public function alterar( Produto $produto ): int;

    /**
     * Verifica a existência de um produto pelo id.
     *
     * @param int $id
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $id ): bool;
}
