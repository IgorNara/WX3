<?php
declare(strict_types=1);

const OPCOES = [
    // Lança todas as exceções
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    // Resultado como matriz associativa
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Reutiliza a mesma conexão. Emula um Singleton
    PDO::ATTR_PERSISTENT => true
];


function getConexao(): PDO {
    $dsn = "mysql:dbname=wx3;host=localhost;charset=utf8";

    try {
        $pdo = new PDO($dsn, "root", "", OPCOES);
    } catch ( PDOException $erro ) {
        respostaJson(true, "ERRO AO CONECTAR COM O BANCO DE DADOS - {$erro->getMessage()}", 500);
    }

    return $pdo;
}


// function gerarId( string $tabela ): array {
//     $con = getConexao();
//     try {
//         $sql = "SELECT id FROM $tabela";

//         $ps = $con->prepare($sql);
//         $ps->execute();
//         $usuarios = $ps->fetchAll(PDO::FETCH_ASSOC);
//     } catch (PDOException $erro) {
//         return ["erro" => true, "msg" => $erro->getMessage()];
//     }
//     $maiorId = 0;
//     foreach ($usuarios as $usuario) {
//         if ($usuario["id"] > $maiorId)
//             $maiorId = $usuario["id"];
//     }
//     return ["erro" => false, "id" => $maiorId + 1];
// }


function respostaJson( bool $erro, string $msg, int $codeStatus, $dados = null ): void {
    header( "Content-Type:application/json;charset=utf-8" );
    die( json_encode( [ "erro" => $erro, "msg" => $msg, "code" => $codeStatus, "dados" => $dados ] ) );
}


function obterLogica(): string {
    $url = $_SERVER[ 'REQUEST_URI' ]; 
    $diretorioRaiz = dirname( $_SERVER[ 'PHP_SELF' ] ); 
    $rotaCompleta = str_replace( $diretorioRaiz, "", $url ); 
    $arrayRota = explode( '/', $rotaCompleta );

    $logica = "/{$arrayRota[1]}";
    if( count( $arrayRota ) > 2 ) {
        for( $i = 2; $i < count( $arrayRota ); $i++ ) {
            if( ! is_numeric( $arrayRota[$i] ) )
                $logica .= "/".$arrayRota[$i];
            else
                $logica .= "/:id";
        }
    }

    return $logica;
}


function salvarImg( array $imagem, string $diretorio ): string|null {
    $extensoes = array("png", "jpg", "jpeg");
    
    if ( isset( $imagem ) && $imagem['error'] === UPLOAD_ERR_OK ) {
        $extensao = pathinfo( $imagem['name'], PATHINFO_EXTENSION );
        $nome = pathinfo( $imagem['name'], PATHINFO_FILENAME );
        $nome = $nome . '.' . $extensao;
        $caminho = $diretorio . $nome;

        if ( ! in_array( $extensao, $extensoes, true ) ) {
            respostaJson(true, "Formato inválido - Somente as extensões 'png', 'jpeg' e 'jpg' são permitidas.", 500);
        }

        if ( file_exists( $caminho ) ) {
            respostaJson(true, "Essa imagem já foi adicionada.", 500);
        }

        if ( ! move_uploaded_file( $imagem['tmp_name'], $caminho ) ) {
            respostaJson( true, "Erro ao salvar imagem.", 500 );
        } 

        return $caminho;
    } 
    return null;
}


function excluirImg( string $caminho ) {
    if ( file_exists( $caminho ) ) {
        unlink( $caminho );
    }
}