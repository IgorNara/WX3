<?php
declare(strict_types = 1);

class Categoria extends Validavel implements JsonSerializable {

    public function __construct( 
        private int $id = 0,
        private string $nome = "",
        private string $descricao = ""
    ){}

    public function __get( string $atributo ): mixed {
        return $this->$atributo ?? throw new RuntimeException( "Erro ao buscar atributo", 500 );
    }

    public function validar(): void {
        // Nome
        if( strlen( $this->nome ) <= 3 || strlen( $this->nome ) > 30 ) 
            $this->problemas[] = "O nome da categoria dete ter no mínimo 4 e no máximo 30 caracteres.";

        // Descrição
        if( strlen( $this->descricao ) <= 3 || strlen( $this->descricao ) > 255 )
            $this->problemas[] = "A descrição da categoria deve ter no mínimo 4 e no máximo 30 caracteres.";
    }


    public function toArray(): array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao
        ];
    }


    public function jsonSerialize(): array {
        return $this->toArray();
    }
}


?>