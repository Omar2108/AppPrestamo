<<?php

$peticionAjax=true;

require_once '../config/APP.php';

if (isset($POST_['usuario_dni_reg']) || isset($_POST['usuario_id_delete'])) {
	
	/*-----Instancia al controlador----*/

	require_once '../controladores/usuarioControlador.php';

	$ins_usuario = new usuarioControlador();

	/*-----PARA AGREGAR USUARIO----*/

	if (isset($_POST['usuario_dni_reg']) && isset($_POST['usuario_nombre_reg'])) {
		echo $ins_usuario->agregar_usuario_controlador();
		
	}

	/*-----PARA AGREGAR USUARIO----*/

	if (isset($_POST['usuario_id_delete'])) {
		echo $ins_usuario->eliminar_usuario_controlador();
	}

} else {
	session_start(['name'=>'SPM']);
	session_unset();
	session_destroy();
	header('location'.SERVERURL.'login/');
	exit();

}
