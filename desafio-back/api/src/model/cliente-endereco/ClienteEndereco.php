<?php

declare(strict_types=1);

class ClienteEndereco extends Validavel implements JsonSerializable {
    public function __construct(
        public ?Cliente $cliente = null,
        public ?Endereco $endereco = null
    ){}


    public function validar(): void {
        if( $this->cliente === null ) {
            $this->problemas[] = "Cliente não informado.";
        }

        if( $this->endereco === null ) {
            $this->problemas[] = "Endereço não informado.";
        }
    }


    public function toArray(): array {
        $array = get_object_vars( $this );
        unset( $array["problemas"] );
        return $array;
    }


    public function jsonSerialize(): array {
        return [
            "cliente" => $this->cliente,
            "endereco" => $this->endereco
        ];
    }
}

?>