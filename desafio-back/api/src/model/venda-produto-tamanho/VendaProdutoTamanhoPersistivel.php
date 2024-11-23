<?php
declare( strict_types=1 );


interface VendaProdutoTamanhoPersistivel {
    /**
     * Retorna todas as relações entre venda, produto e tamanho.
     *
     * @return array<VendaProdutoTamanho>
     * @throws RuntimeException
     */
    public function obterTodos(): array;

    /**
     * Adiciona uma relação entre venda, produto e tamanho.
     *
     * @param VendaProdutoTamanho $vendaProdutoTamanho
     * @return int id gerado
     * @throws RuntimeException
     */
    public function inserir( VendaProdutoTamanho $vendaProdutoTamanho ): int;

    /**
     * Atualiza uma relação entre venda, produto e tamanho.
     *
     * @param VendaProdutoTamanho $vendaProdutoTamanho
     * @return int linhas afetadas
     * @throws RuntimeException
     */
    public function alterar( VendaProdutoTamanho $vendaProdutoTamanho ): int;

    /**
     * Remove uma relação entre venda, produto e tamanho pelo id.
     *
     * @param int $idVenda
     * @param int $idProduto
     * @param int $idTamanho
     * @return int linhas afetadas
     * @throws RuntimeException
     */
    public function excluirPeloId( int $idVenda, int $idProduto, int $idTamanho ): int;

    /**
     * Obtem uma relação entre venda, produto e tamanho pelo id.
     *
     * @param int $idVenda
     * @param int $idProduto
     * @param int $idTamanho
     * @return VendaProdutoTamanho
     * @throws RuntimeException
     */
    public function obterPeloId( int $idVenda, int $idProduto, int $idTamanho ): VendaProdutoTamanho;

    /**
     * Obtem todos os produtos e seus tamanhos referentes a uma venda.
     *
     * @param int $idVenda
     * @return array<VendaProdutoTamanho>
     * @throws RuntimeException
     */
    public function obterPeloIdVenda( int $idVenda ): array;

    /**
     * Verifica a existência de uma relação entre venda, produto e tamanho pelo id.
     *
     * @param int $idVenda
     * @param int $idProduto
     * @param int $idTamanho
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComIdVenda( int $idVenda ): bool;
}
