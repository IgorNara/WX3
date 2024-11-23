<?php
declare(strict_types = 1);


class Cliente {
    private array $problemas = [];

    public function __construct(
        public int $id = 0,
        public string $nomeCompleto = "",
        public string $cpf = "",
        public string $dataNascimento = "",
        public string $senha = ""
    ){
        $this->setCpf( $cpf );
        $this->setDataNascimento( $dataNascimento );
    }


    public function getProblemas(): array {
        return $this->problemas;
    }


    public function setCpf( $cpf ): void {
        $cpfSemPonto = str_replace( ".", "", $cpf );
        $cpfLimpo = str_replace( "-", "", $cpfSemPonto );
        $this->cpf = $cpfLimpo;
    }


    public function setDataNascimento( $dataNascimento ): void {
        $this->dataNascimento = join( "-", array_reverse( explode( "/", $this->dataNascimento ) ) );
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
        if( strlen( $this->nomeCompleto ) < 10 || strlen( $this->nomeCompleto ) > 60 )
            $this->problemas[] = "O nome completo deve ter no mínimo 10 e no máximo 60 caracteres";

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
}


?>