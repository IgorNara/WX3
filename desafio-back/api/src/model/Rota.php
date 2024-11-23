<?php
declare(strict_types=1);

class Rota {
    private string $logica;
    private string $metodo;
    private string $parametro;
    private Requisicao $req;
    private Array $dados;

    public function __construct( Array $server , Array $dados ){
        $this->req = new Requisicao( $server );
        $this->logica = $this->req->getLogica();
        $this->metodo = $this->req->getMetodo();
        $this->parametro = $this->req->getParametros();
        $this->dados = $dados;
    }

    
    public function executarRota( Array $rotas ) { 
       $rota =  $rotas[ $this->logica ][ $this->metodo ];
       if($rota){
            try{
                if( $this->dados ) $rota( $this->dados );
                else if ( $this->parametro ) $rota( $this->parametro );
                else $rota();
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