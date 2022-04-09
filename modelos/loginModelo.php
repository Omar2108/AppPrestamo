<<?php

require_once 'modeloPrincipal.php';


class loginModelo extends modeloPrincipal {
	
	/*-----modelo para iniciar sesion----*/

	protected static function iniciar_sesion_modelo($datos){
		$sql=modeloPrincipal::conectar->prepare("SELECT * FROM usuarios WHERE usuario_usuario= :usuario AND usuario_clave= :clave AND usuario_estado= 'Activa'");

		$sql=bindParam(":usuario", $datos['usuario']);
		$sql=bindParam(":clave", $datos['clave']);
		$sql->execute();
		return $sql;
	}
	
}