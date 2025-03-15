<?php
declare( strict_types=1 );


class TamanhoPersistivelEmBDR extends PersistivelEmBDR implements TamanhoPersistivel {

    /** @inheritDoc */
    public function obterTodos(): array {
        $sql = "SELECT id, sigla AS stringSigla FROM tamanho";
        $tamanhos = $this->carregarObjetosDaClasse( $sql, Tamanho::class, [], "Erro ao carregar tamanhos." );
        foreach( $tamanhos as $tamanho ) {
            $tamanho->sigla = CampoUnicoTamanho::from( $tamanho->stringSigla );
        }
        return $tamanhos;
    }


    /** @inheritDoc */
    public function obterPeloId( int $id ): Tamanho {
        $sql = "SELECT id, sigla AS stringSigla FROM tamanho WHERE id = ?";
        $tamanho = $this->primeiroObjetoDaClasse( $sql, Tamanho::class, [ $id ], "Erro ao carregar tamanho." );
        $tamanho->sigla = CampoUnicoTamanho::from( $tamanho->stringSigla );
        return $tamanho;
    }


    /** @inheritDoc */
    public function existeComId( int $id ): bool {
        $sql = "SELECT id FROM tamanho WHERE id = ?";
        $tamanho = $this->primeiroObjetoDaClasse( $sql, Tamanho::class, [ $id ], "Erro ao verificar tamanho." );
        return $tamanho !== null;
    }
}
?>