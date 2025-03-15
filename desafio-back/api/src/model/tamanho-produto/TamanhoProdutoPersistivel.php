<?php
declare( strict_types=1 );


interface TamanhoProdutoPersistivel {
    /**
     * Retorna todos os tamanhos de todos os produtos
     *
     * @return array<TamanhoProduto>
     * @throws RuntimeException
     */
    public function obterTodos(): array;

    /**
     * Adiciona um tamanho a um produto
     *
     * @param TamanhoProduto $tamanhoProduto
     * @throws RuntimeException
     */
    public function inserir( TamanhoProduto $tamanhoProduto ): void;

    /**
     * Obtem os tamanhos de um produto
     *
     * @param int $idProduto 
     * @return array<TamanhoProduto>
     * @throws RuntimeException
     */
    public function obterPeloId( int $idProduto ): array;

    /**
     * Obtem o tamanho de um produto
     * 
     * @param int $idProduto
     * @param int $idTamanho
     * @return TamanhoProduto
     * @throws RuntimeException
     */
    public function obterPeloIdProdutoTamanho( int $idProduto, int $idTamanho ): TamanhoProduto;

    /**
     * Atualiza a quantidade de um tamanho de produto
     *
     * @param TamanhoProduto $tamanhoProduto
     * @return int linhas afetadas
     * @throws RuntimeException
     */
    public function alterar( TamanhoProduto $tamanhoProduto ): int;

    /**
     * Verifica se existe algum tamanho registrado para o produto
     *
     * @param int $idProduto 
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $idProduto  ): bool;
}
