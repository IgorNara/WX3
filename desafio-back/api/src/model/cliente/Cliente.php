<?php
declare(strict_types = 1);


class Cliente extends Validavel implements JsonSerializable {

    public function __construct(
        private int $id = 0,
        private string $nomeCompleto = "",
        private string $cpf = "",
        private string $dataNascimento = "",
        private string $senha = ""
    ){
        $this->setCpf( $cpf );
        $this->setDataNascimento( $dataNascimento );
    }

    public function __get( string $atributo ): mixed {
        return $this->$atributo ?? throw new RuntimeException( "Erro ao buscar atributo", 500 );
    }

    public function setCpf( $cpf ): void {
        $cpfSemPonto = str_replace( ".", "", $cpf );
        $cpfLimpo = str_replace( "-", "", $cpfSemPonto );
        $this->cpf = $cpfLimpo;
    }


    public function setDataNascimento( $dataNascimento ): void {
        $this->dataNascimento = join( "-", array_reverse( explode( "/", $dataNascimento ) ) );
    }


    public function validarSenha(): void {
        if ( ( strlen( $this->senha ) <= 6 ) || ( strlen( $this->senha ) > 20 ) ) {
            $this->problemas[] = "A senha deve ter entre 7 e 20 caracteres.";
        }
        else if ( ! preg_match( "/\d/", $this->senha ) ) {
            $this->problemas[] = "A senha deve ter ao menos um número.";
        }
        else if ( ! preg_match( "/\W/", $this->senha ) ) {
            $this->senha[] = "A senha deve ter ao menos um caractere especial.";
        }
    }


    public function validar(): void {
        // Nome Completo
        if( strlen( $this->nomeCompleto ) < 5 || strlen( $this->nomeCompleto ) > 60 )
            $this->problemas[] = "O nome completo deve ter no mínimo 5 e no máximo 60 caracteres";

        // CPF
        if( strlen( $this->cpf ) !== 11 )
            $this->problemas[] = "O CPF deve ter exatamente 11 caracteres numéricos.";

        // Data Nascimento
        $arrayData = explode( "-", $this->dataNascimento );
        $ano = intval( $arrayData[0] );
        $mes = intval( $arrayData[1] );
        $dia = intval( $arrayData[2] );
        if( $this->dataNascimento === "" )
            $this->problemas[] = "Fornecer a data de nascimento é obrigatório.";
        if( $ano > ( intval( date("Y") ) - 18 ) )
            $this->problemas[] = "Só é permitido maiores de 18 anos.";
        elseif( $ano === ( intval( date("Y") ) - 18 ) ) {
            if( $mes > intval( date("m") ) ) 
                $this->problemas[] = "Só é permitido maiores de 18 anos.";
            else if( $mes === intval( date("m") ) && $dia > intval( date("d") ) )
                $this->problemas[] = "Só é permitido maiores de 18 anos.";
        }
    }


    public function toArray(): array {
        return [
            "id" => $this->id,
            "nomeCompleto" => $this->nomeCompleto,
            "cpf" => $this->cpf,
            "dataNascimento" => $this->dataNascimento,
            "senha" => $this->senha
        ];
    }


    public function jsonSerialize(): array {
        return $this->toArray();
    }
}


?>