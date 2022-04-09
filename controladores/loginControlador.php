<<?php

if ($peticionAjax) {
	require_once '../modelos/loginModelo.php';
} else {
	require_once './modelos/loginModelo.php';
}

/**
 * 
 */
class loginControlador extends loginModelo {
	
	/*-----Controlador para iniciar sesion----*/

	public function iniciar_sesion_controlador(){

		$usuario=modeloPrincipal::limpiar_cadena($_POST['usuario_log']);
		$clave=modeloPrincipal::limpiar_cadena($_POST['clave_log']);

		/*-----Comprobar campos vacios----*/

		if ($usuario=="" || $clave=="") {
			echo "<script>
			    Swal.fire({
                    title: 'Ocurrio un error inesperado',
                    text: 'No has llenado todos los campos que son requeridos',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });

			</script>";

			exit();
		}

		/*-----Verificar integridad de los datos----*/

		if (modeloPrincipal::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)) {
			echo "<script>
			    Swal.fire({
                    title: 'Ocurrio un error inesperado',
                    text: 'El nombre de usuario no coincide con el formato solicitado',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });

			</script>";
			exit();
		}

		if (modeloPrincipal::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave)) {
			echo "<script>
			    Swal.fire({
                    title: 'Ocurrio un error inesperado',
                    text: 'La calve no coincide con el formato solicitado',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });

			</script>";

			exit();
		}
		
		$clave=modeloPrincipal::encryption($clave);

		$datos_login=[
			"usuario"=>$usuario,
			"clave"=>$clave
		];

		$datos_cuenta=loginModelo::iniciar_sesion_modelo($datos_login);

		if ($datos_cuenta->rowCount()==1) {
			$row=$datos_cuenta->fetch();

			session_start(['name'=>'SPM']);

			$_SESSION['id_spm']=$row['usuario_id'];
			$_SESSION['nombre_spm']=$row['usuario_nombre'];
			$_SESSION['apellido_spm']=$row['usuario_apellido'];
			$_SESSION['usuario_spm']=$row['usuario_usuario'];
			$_SESSION['privilegio_spm']=$row['usuario_privilegio'];
			$_SESSION['token_spm']=md5(uniqid(mt_rand(), true));

			return header("Location:".SERVERURL."home/");

		} else {
			echo "<script>
			    Swal.fire({
                    title: 'Ocurrio un error inesperado',
                    text: 'El usuario o clave son incorrecto',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });

			</script>";
		}
		

	} /*-----FIN DEL CONTROLADOR----*/


	/*-----Controlador Cerrar sesion----*/


	public function cierre_sesion_controlador(){
		session_start(['name'=>'SPM']);

		$token=modeloPrincipal::decryption($_POST['token']);
		$usuario=modeloPrincipal::decryption($_POST['usuario']);

		if ($token==$_SESSION['token_spm'] && $usuario==$_SESSION['usuario_spm']) {
			session_unset();
			session_destroy();
			$alerta=[
				"Alerta"=>"redireccionar",
				"URL"=>SERVERURL."login/"
			];
		} else {

			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Error al cerrar la sesion ",
				"Texto"=>"No se puedo cerrar la sesion en el sistema",
				"icon"=>"error"
			];
			echo json_decode($alerta);
			
		}
		

	}/*-----FIN DEL CONTROLADOR----*/

	
	
}