<?php
	
	require_once "./config/APP.php";
	require_once "./controladores/vistasControlador.php";
	/*
	require_once "./controladores/usuarioControlador.php";
	require_once "./controladores/loginControlador.php";
	*/

	$plantilla = new vistasControlador();
	$plantilla->obtener_plantilla_controlador();