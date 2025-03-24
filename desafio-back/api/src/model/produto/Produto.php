<?php
declare(strict_types = 1);


class Produto extends Validavel implements JsonSerializable {
    public function __construct(
        private int $id = 0,
        private ?Categoria $categoria = null,
        private string $nome = "",
        private array $cores = [],
        private array $urls = [],
        private float $preco = 0.0,
        private string $descricao = "",
        private string $dataCadastro = "",
        private float $peso = 0.0
    ){
      $this->setDataCadastro( $dataCadastro );
    }

    public function __get( string $atributo ): mixed {
        return $this->$atributo ?? throw new RuntimeException( "Erro ao buscar atributo", 500 );
    }

    public function setCategoria( Categoria $categoria ): void {
        $this->categoria = $categoria;
    }


    public function setUrls( array $urls ): void {
        $this->urls = $urls;
    }


    public function setCores( array $cores ): void {
        $this->cores = $cores;
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


    public function jsonSerialize(): array {
        return $this->toArray();
    }
}


?>