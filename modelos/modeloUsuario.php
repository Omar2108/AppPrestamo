<<?php

require_once 'modeloPrincipal.php';



class modeloUsuario extends modeloPrincipal {

	/*-----modelo para agregrar usuarios----*/
	
	protected static function agregar_usuario_modelo($datos){
		$sql= modeloPrincipal::conectar()->prepare("INSERT INTO 'usuarios'('usuario_dni', 'usuario_nombre', 'usuario_apellido','usuario_telefono','usuario_direccion','usuario_email','usuario_usuario','usuario_clave','usuario_estado','usuario_privilegio') VALUES(':DNI', ':nombre', ':apellido',':telefono',':direccion',':email',':usuario',':clave',':estado',':privilegio');");

		$sql->bindParam(":DNI", $datos['DNI']);
		$sql->bindParam(":nombre", $datos['nombre']);
		$sql->bindParam(":apellido", $datos['apellido']);
		$sql->bindParam(":telefono", $datos['telefono']);
		$sql->bindParam(":direccion", $datos['direccion']);
		$sql->bindParam(":email", $datos['email']);
		$sql->bindParam(":usuario", $datos['usuario']);
		$sql->bindParam(":clave", $datos['clave']);
		$sql->bindParam(":estado", $datos['estado']);
		$sql->bindParam(":privilegio", $datos['privilegio']);

		$sql->execute();

		return $sql;
		
	}

	/*-----modelo para eliminar usuarios----*/

	protected static function eliminar_usuario_modelo($id){
		$sql=modeloPrincipal::conectar()->prepare("DELETE FROM usuarios WHERE usuario_id= :id");

		$sql->bindParam(":id", $datos['usuario_id']);
		$sql->execute();
		return $sql;
		
	}
}