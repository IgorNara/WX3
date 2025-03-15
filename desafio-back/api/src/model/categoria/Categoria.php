<?php
declare(strict_types = 1);

class Categoria implements JsonSerializable {
    private array $problemas = [];

    public function __construct( 
        public int $id = 0,
        public string $nome = "",
        public string $descricao = ""
    ){}


    public function getProblemas(): array {
        return $this->problemas;
    }


    public function validar(): void {
        // Nome
        if( strlen( $this->nome ) <= 3 || strlen( $this->nome ) > 30 ) 
            $this->problemas[] = "O nome da categoria dete ter no mínimo 4 e no máximo 30 caracteres.";

        // Descrição
        if( strlen( $this->descricao ) <= 3 || strlen( $this->descricao ) > 255 )
            $this->problemas[] = "A descrição da categoria deve ter no mínimo 4 e no máximo 30 caracteres.";
    }


    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao
        ];
    }
}


?>