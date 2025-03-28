<?php
declare( strict_types=1 );


interface CategoriaPersistivel {
    /**
     * Retorna todas as categorias.
     *
     * @return array<Categoria>
     * @throws RuntimeException
     */
    public function obterTodos(): array;

    /**
     * Adiciona uma categoria.
     *
     * @param Categoria $categoria
     * @return int id gerado
     * @throws RuntimeException
     */
    public function inserir( Categoria $categoria ): int;

    /**
     * Atualiza uma categoria. Retorna true se encontrou para atualização.
     *
     * @param Categoria $categoria
     * @return bool 
     * @throws RuntimeException
     */
    public function alterar( Categoria $categoria ): bool;

    /**
     * Remove uma categoria pelo id.
     *
     * @param int $id
     * @return bool
     * @throws RuntimeException
     */
    public function excluirPeloId( int $id ): bool;

    /**
     * Obtem uma categoria pelo id.
     *
     * @param int $id
     * @return ?Categoria
     * @throws RuntimeException
     */
    public function obterPeloId( int $id ): ?Categoria;

    /**
     * Verifica a existência de uma categoria pelo id.
     *
     * @param int $id
     * @return bool indica se existe
     * @throws RuntimeException
     */
    public function existeComId( int $id ): bool;
}
