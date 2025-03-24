<?php

declare(strict_types=1);

class ClienteEndereco extends Validavel implements JsonSerializable {
    public function __construct(
        private ?Cliente $cliente = null,
        private ?Endereco $endereco = null
    ){}


    public function __get( string $atributo ): mixed {
        return $this->$atributo ?? throw new RuntimeException( "Erro ao buscar atributo", 500 );
    }


    public function validar(): void {
        if( $this->cliente === null ) {
            $this->problemas[] = "Cliente não informado.";
        }

        if( $this->endereco === null ) {
            $this->problemas[] = "Endereço não informado.";
        }
    }


    public function toArray(): array {
        return [
            "cliente" => $this->cliente,
            "endereco" => $this->endereco
        ];
    }


    public function jsonSerialize(): array {
        return $this->toArray();
    }
}

?>