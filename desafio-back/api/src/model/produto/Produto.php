<?php
declare(strict_types = 1);


class Produto extends Validavel implements JsonSerializable {
    public function __construct(
        public int $id = 0,
        public ?Categoria $categoria = null,
        public string $nome = "",
        public array $cores = [],
        public array $urls = [],
        public float $preco = 0.0,
        public string $descricao = "",
        public string $dataCadastro = "",
        public float $peso = 0.0
    ){
      $this->setDataCadastro( $dataCadastro );
    }
    

    public function setDataCadastro( $dataCadastro ): void {
        $this->dataCadastro = join( "-", array_reverse( explode( "/", $dataCadastro ) ) );
    }


    public function validar(): void {
        // Nome
        if( strlen( $this->nome ) <= 3 || strlen( $this->nome ) > 30 )
            $this->problemas[] = "O nome do produto deve ter no mínimo 4 e no máximo 30 caracteres.";

        // Cores
        if( empty( $this->cores ) )
            $this->problemas[] = "O produto deve ter no mínimo uma cor.";

        // Urls imagens
        if( empty( $this->urls ) ) 
            $this->problemas[] = "O produto deve ter no mínimo uma imagem.";

        // Preço
        if( $this->preco <= 0 )
            $this->problemas[] = "O preço do produto deve ser maior que zero.";

        // Descrição
        if( strlen( $this->descricao ) <= 3 || strlen( $this->descricao ) > 255 )
            $this->problemas[] = "A descrição do produto deve ter no mínimo 4 e no máximo 255 caracteres.";

        // Data de cadastro
        if( strlen( $this->dataCadastro ) !== 10 )
            $this->problemas[] = "Data inválida.";

        // Peso
        if( $this->peso <= 0 )
            $this->problemas[] = "O peso do produto precisa ser maior que zero.";
    }


    public function toArray(): array {
        $array = get_object_vars( $this );
        unset( $array["problemas"] );
        return $array;
    }


    public function jsonSerialize(): array {
        return [
            "id" => $this->id,
            "categoria" => $this->categoria,
            "nome" => $this->nome,
            "cores" => $this->cores,
            "urls" => $this->urls,
            "preco" => $this->preco,
            "descricao" => $this->descricao,
            "dataCadastro" => $this->dataCadastro,
            "peso" => $this->peso,
        ];
    }
}


?>