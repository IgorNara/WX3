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
     * @return int
     * @throws RuntimeException
     */
    public function inserir( TamanhoProduto $tamanhoProduto ): int;

    /**
     * Obtem os tamanhos de um produto
     *
     * @param int $idProduto 
     * @return array<TamanhoProduto>
     * @throws RuntimeException
     */
    public function obterPeloIdProduto( int $idProduto ): array;

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
     * @return bool indicando se encontrou para edição
     * @throws RuntimeException
     */
    public function alterar( TamanhoProduto $tamanhoProduto ): bool;

    /**
     * Verifica se existe algum tamanho registrado para o produto
     *
     * @param int $idProduto 
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $idProduto  ): bool;
}
