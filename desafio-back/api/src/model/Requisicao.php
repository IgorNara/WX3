<?php
declare( strict_types=1 );

class Requisicao {
    private array $dadosReq;
    private string $url = '';
    private string $arquivoAtual = '';
    private string $diretorioRaiz = ''; 
    private string $rota = ''; 
    private string $metodo = '';
    private array $arrayRota=[];
    private string $logica = '';
    private array $parametros = [];
    private string $logicaComId = '';


    public function __construct( array $dadosReq ) {
        $this->dadosReq = $dadosReq;
        $this->destrinchaRequisicao();
    }


    private function destrinchaRequisicao(){
        $this->url = $this->dadosReq[ 'REQUEST_URI' ]; 

        $this->arquivoAtual = $this->dadosReq[ 'PHP_SELF' ]; 

        $this->diretorioRaiz = dirname( $this->arquivoAtual ); 

        $this->rota = str_replace( $this->diretorioRaiz, '', $this->url ); 

        $this->metodo = $this->dadosReq[ 'REQUEST_METHOD' ];

        $this->arrayRota = explode( '/', $this->rota );

        $this->logica = $this->arrayRota[1];

        if( count( $this->arrayRota ) > 2 ) {
            for( $i = 2; $i < count( $this->arrayRota ); $i++ ) {
                if( is_numeric( $this->arrayRota[$i] ) )
                    $this->parametros[] = $this->arrayRota[$i];
                else
                    $this->logica .= "/".$this->arrayRota[$i];
            }
        }
        
        $this->logicaComId = ( ! empty( $this->parametros ) ) ? '/'.$this->logica.'/:id' : '/'.$this->logica;
    }


    public function getLogica(): string {
        if( empty( $this->parametros ) )
            return $this->logica;
        return $this->logicaComId;
    }


    public function getMetodo(): string {
        return $this->metodo;
    }


    public function getParametros(): array {
        return $this->parametros;
    }
}
?>