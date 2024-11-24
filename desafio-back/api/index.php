<?php
declare(strict_types=1);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/model/funcoes-util.php';

$dados = json_decode( file_get_contents( "php://input" ), true ) ?? [];

$logica = obterLogica();
$conexao = getConexao();

$rota = new Rota( $_SERVER, $dados );
$arrayRotas = require_once __DIR__ . "/src/rotas.php";

if( $logica === "/cliente/logar" ) {
    $rotas = require_once __DIR__ . "/src/controller/rotas-cliente.php";
    $rota->executarRota( $rotas );
}
else {
    require_once __DIR__ . '/src/model/autenticar.php';
    foreach( $arrayRotas as $rotas ) {
        if( isset( $rotas[$logica] ) ) 
            $rota->executarRota( $rotas );
    }
}
?>