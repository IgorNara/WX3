<?php
declare( strict_types=1 );


interface ProdutoPersistivel {
    /**
     * Retorna uma lista de produtos ordenada em ordem decrescente com base no seu total de vendas
     * 
     * @return array<Produto>
     * @throws RuntimeException
     */
    public function obterTodos(): array;

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
     * @return bool
     * @throws RuntimeException
     */
    public function excluirPeloId( int $id ): bool;

    /**
     * Obtem um produto pelo id.
     *
     * @param int $id
     * @return ?Produto
     * @throws RuntimeException
     */
    public function obterPeloId( int $id ): ?Produto;

    /**
     * Atualiza um produto.
     *
     * @param Produto $produto
     * @return bool indicando se encontrou para edição
     * @throws RuntimeException
     */
    public function alterar( Produto $produto ): bool;

    /**
     * Verifica a existência de um produto pelo id.
     *
     * @param int $id
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $id ): bool;

    /**
     * Retorna uma lista de produtos ordenada em ordem decrescente com base no seu total de vendas
     * 
     * @return array<Produto>
     * @throws RuntimeException
     */
    public function rankProdutosMaisVendidos(): array;
}
