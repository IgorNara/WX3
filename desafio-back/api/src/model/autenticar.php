<?php

session_start();
if ( !isset($_SESSION['usuario']) ){
	http_response_code( 401 );
	die();
} 	
else{
	if ( isset( $_SESSION['ultima_atividade'] ) && ( time() - $_SESSION['ultima_atividade'] > 20000 ) ) {
		session_unset();     
		session_destroy();  
		http_response_code( 401 );
		die();
	} 
	else{
		$_SESSION['ultima_atividade'] = time();
	}
}

?>