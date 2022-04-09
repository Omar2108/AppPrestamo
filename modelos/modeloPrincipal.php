<?php

if ($peticionAjax) {
	require_once '../config/SERVER.PHP';
} else {
	require_once './config/SERVER.PHP';
}


class modeloPrincipal{
	/*-----Funcion para conexion a la BD----*/
	protected static function conectar(){
		$server = "localhost";
		$user = "root";
		$password = "";
		$dbname = "prestamos";

		$conexion = new PDO('mysql:host=localhost;dbname=prestamos', $user, $password);

		if ($conexion) {
            echo "<script type=\"text/javascript\"> alert ('Conectado a la DB');";
            echo "</script>";
		} else{
            echo "<script type=\"text/javascript\"> alert ('No se Puedo establecer conexion a la DB');";
            echo "</script>";
		}
		
		return $conexion;
		
	}

	/*-----Funcion para consultas simples----*/
	protected static function ejecutar_consultas_simples($consulta){
		$sql = self::conectar()->prepare($consulta);
		$sql->execute();
		return $sql;

	}

	/*-----Funcion para encriptar cadenas----*/

	public function encryption($string){
		$output=FALSE;
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
		$output=base64_encode($output);
		return $output;
	}


	/*-----Funcion para deseencriptar cadenas----*/

	protected static function decryption($string){
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
		return $output;
	}

	/*-----Funcion para generar codigo aleatorios----*/

	protected static function generar_codigo_aleatorio($letra, $longitud, $numero){
		for ($i=1; $i <= $longitud; $i++) { 
			$longitud= rand(0,9);
			$letra .= $longitud;
		}

		return $letra."-".$numero;

	}

	/*-----Funcion para limpiar cadenas----*/

	protected static function limpiar_cadena($cadena){
		$cadena = trim($cadena);
		$cadena = stripcslashes($cadena);
		$cadena = str_ireplace("<script>", "", $cadena);
		$cadena = str_ireplace("</script>", "", $cadena);
		$cadena = str_ireplace("<script src", "", $cadena);
		$cadena = str_ireplace("<script type=", "", $cadena);
		$cadena = str_ireplace("SELECT * FROM", "", $cadena);
		$cadena = str_ireplace("DELETE FROM", "", $cadena);
		$cadena = str_ireplace("INSERT INTO", "", $cadena);
		$cadena = str_ireplace("DROP TABLE", "", $cadena);
		$cadena = str_ireplace("DROP DATABASE", "", $cadena);
		$cadena = str_ireplace("TRUNCATE TABLE", "", $cadena);
		$cadena = str_ireplace("SHOW TABLE", "", $cadena);
		$cadena = str_ireplace("SHOW DATABASE", "", $cadena);
		$cadena = str_ireplace("<?php", "", $cadena);
		$cadena = str_ireplace("?>", "", $cadena);
		$cadena = str_ireplace("--", "", $cadena);
		$cadena = str_ireplace("<", "", $cadena);
		$cadena = str_ireplace(">", "", $cadena);
		$cadena = str_ireplace("[", "", $cadena);
		$cadena = str_ireplace("]", "", $cadena);
		$cadena = str_ireplace("{", "", $cadena);
		$cadena = str_ireplace("}", "", $cadena);
		$cadena = str_ireplace("^", "", $cadena);
		$cadena = str_ireplace("==", "", $cadena);
		$cadena = str_ireplace(";", "", $cadena);
		$cadena = str_ireplace("::", "", $cadena);
		$cadena = stripcslashes($cadena);
		$cadena = trim($cadena);

		return $cadena;
	}

    /*-----Funcion para verificar datos----*/

    protected static function verificar_datos ($filtro, $cadena){
    	if (preg_match("/^".$filtro."$/",$cadena)) {
    		return false;
    	} else {
    		return true;
    	}
    	

    }

    /*-----Funcion para verificar fechas----*/

    protected static function verificar_fecha($fecha){
    	$valores=explode('-', $fecha);

    	if (count($valores)==3 AND checkdate($valores[1], $valores[2], $valores[0]) ) {
    		return false;
    		
    	} else {
    		return true;
    		
    	}
    	

    }

    /*-----Funcion paginador de tablas----*/

    protected static function paginador_tablas($paginas, $Npaginas, $url, $botones){
	    $tabla = '<nav aria-label="Page navigation example"><ul class="pagination justify-content-center">';

	    if ($paginas==1) {
	    	$tabla .= '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-left"></i></a></li>';
	    	
	    } else {
	    	$tabla .= '
	    	<li class="page-item"><a class="page-link" href="'.$url.'1/"><i class="fas fa-angle-double-left"></i></a></li>
	    	<li class="page-item"><a class="page-link" href="'.$url.($paginas-1).'/">Anterior</a></li>
	    	';	
	    }

	    $ci = 0;

	    for ($i=$paginas; $i <= $Npaginas ; $i++) { 
	    	if ($ci >= $botones) {
	    		break;
	    	}

	    	if ($paginas=$i) {
	    		$tabla .= '<li class="page-item"><a class="page-link active" href="'.$url.$i.'/">'.$i.'</a></li>';
	    	} else {
	    		$tabla .= '<li class="page-item"><a class="page-link" href="'.$url.$i.'/">'.$i.'</a></li>';
	    	}

	    	$ci++;
	    	
	    }



	    if ($paginas==$Npaginas) {
	    	$tabla .= '<li class="page-item disabled"><a class="page-link"><i class="fas fa-angle-double-right"></i></a></li>';
	    	
	    } else {
	    	$tabla .= '
	    	<li class="page-item"><a class="page-link" href="'.$url.($paginas+1).'1/">Siguiente</i></a></li>
	    	<li class="page-item"><a class="page-link" href="'.$url.$Npaginas.'/"><i class="fas fa-angle-double-right"></i></a></li>
	    	';	
	    }
	    

		$tabla .= '</ul></nav>';

		return $tabla;


    }



}
