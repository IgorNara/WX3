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
     * Obtem todos os produtos e seus tamanhos referentes a uma venda.
     *
     * @param int $idVenda
     * @return array<VendaProdutoTamanho>
     * @throws RuntimeException
     */
    public function obterPeloId( int $idVenda ): array;

    /**
     * Verifica a existência de uma relação entre venda, produto e tamanho pelo id.
     *
     * @param int $idVenda
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $idVenda ): bool;
}
