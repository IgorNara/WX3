<?php
declare(strict_types=1);

class Rota {
    private string $logica;
    private string $metodo;
    private array $parametros;
    private Requisicao $req;
    private Array $dados;

    public function __construct( Array $server , Array $dados ){
        $this->req = new Requisicao( $server );
        $this->logica = $this->req->getLogica();
        $this->metodo = $this->req->getMetodo();
        $this->parametros = $this->req->getParametros();
        $this->dados = $dados;
    }

    
    public function executarRota( Array $rotas ) { 
       $rota =  $rotas[ $this->logica ][ $this->metodo ];
       if($rota){
            try{
                if( ! empty( $this->dados ) ) $rota( $this->dados );
                else if ( ! empty( $this->parametros ) ) $rota( $this->parametros );
            }
            catch (RuntimeException $e) {
                respostaJson( true, $e->getMessage(), 400 );
            }
            catch (Exception $e) {
                respostaJson( true, $e->getMessage(), 500 );
            }
       }else{
            respostaJson( true , "Rota inexistente." , 404 );
       }
    }
}

?>