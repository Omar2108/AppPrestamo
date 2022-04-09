<<?php

if ($peticionAjax) {
	require_once '../modelos/modeloUsuario.php';
} else {
	require_once './modelos/modeloUsuario.php';
}


/**
 * 
 */
class usuarioControlador extends modeloUsuario {

	/*-----controlador para agregrar usuarios----*/
	
	public function agregar_usuario_controlador(){

		$dni=modeloPrincipal::limpiar_cadena($_POST['usuario_dni_reg']);
		$nombre=modeloPrincipal::limpiar_cadena($_POST['usuario_nombre_reg']);
		$apellido=modeloPrincipal::limpiar_cadena($_POST['usuario_apellido_reg']);
		$telefono=modeloPrincipal::limpiar_cadena($_POST['usuario_telefono_reg']);
		$direccion=modeloPrincipal::limpiar_cadena($_POST['usuario_direccion_reg']);

		$usuario=modeloPrincipal::limpiar_cadena($_POST['usuario_usuario_reg']);
		$email=modeloPrincipal::limpiar_cadena($_POST['usuario_email_reg']);
		$clave1=modeloPrincipal::limpiar_cadena($_POST['usuario_clave_1_reg']);
		$clave2=modeloPrincipal::limpiar_cadena($_POST['usuario_clave_2_reg']);

		$privilegio=modeloPrincipal::limpiar_cadena($_POST['usuario_privilegio_reg']);

		/*-----comprobar los campos vacios obligatorios----*/

		if ($dni=="" || $nombre=="" || $apellido=="" || $usuario=="" || $clave1=="" ||$clave2=="" ) {

			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No has llenado todos los campos obligatorios",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
		}

		/*-----Verificando la integridad de los datos----*/

		if (modeloPrincipal::verificar_datos("[0-9-]{10,20}",$dni)) {

			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El DNI no coincide con el formato solicitado",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
			
		}

		if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,35}",$nombre)) {

			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El Nombre no coincide con el formato solicitado",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
			
		}


		if (modeloPrincipal::verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{4,35}",$apellido)) {

			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El apellido no coincide con el formato solicitado",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
			
		}

		if ($telefono!="") {
			if (modeloPrincipal::verificar_datos("[0-9()+]{8,20}",$telefono)) {

			    $alerta=[
				    "Alerta"=>"simple",
				    "Titulo"=>"Ocurrio un error inesperado",
				    "Texto"=>"El telefono no coincide con el formato solicitado",
				    "icon"=>"error"
			    ];

			    echo json_decode($alerta);
			    exit;
			
		    }
		}

		if ($direccion!="") {
			if (modeloPrincipal::verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,#\- ]{4,190}",$direccion)) {

			    $alerta=[
				    "Alerta"=>"simple",
				    "Titulo"=>"Ocurrio un error inesperado",
				    "Texto"=>"La direccion no coincide con el formato solicitado",
				    "icon"=>"error"
			    ];

			    echo json_decode($alerta);
			    exit;
			
		    }
		}
		


		if (modeloPrincipal::verificar_datos("[a-zA-Z0-9]{1,35}",$usuario)) {

			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El nombre de usuario no coincide con el formato solicitado",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
			
		}


		if (modeloPrincipal::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave1 || modeloPrincipal::verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$clave2)) {

			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"Las CLAVES no coincide con el formato solicitado",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
			
		}

		/*-----comprobando DNI----*/

		$check_dni= modeloPrincipal::ejecutar_consultas_simples("SELECT usuario_dni FROM usuarios WHERE usuario_dni='$dni'";);

		if ($check_dni->rowCount()>0) {
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El DNI ingresado ya se encuentra registrado en el sistema",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
		}

		/*-----comprobando usuario----*/

		$check_user = modeloPrincipal::ejecutar_consultas_simples("SELECT usuario_usuario FROM usuarios WHERE usuario_usuario='$usuario'";);

		if ($check_user->rowCount()>0) {
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El USUARIO ingresado ya se encuentra registrado en el sistema",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
		}

		/*-----comprobando email----*/

		if ($email!="") {
			if (filter_var($email, FLITER_VALIDATE_EMAIL)) {
				$check_email = modeloPrincipal::ejecutar_consultas_simples("SELECT usuario_email FROM usuarios WHERE usuario_email='$email'";);

				if ($check_email->rowCount()>0) {

			        $alerta=[
				        "Alerta"=>"simple",
				        "Titulo"=>"Ocurrio un error inesperado",
				        "Texto"=>"El EMAIL ingresado ya se encuentra registrado en el sistema",
				        "icon"=>"error"
			        ];

			        echo json_decode($alerta);
			        exit;
		        }

			} else {
				$alerta=[
				    "Alerta"=>"simple",
				    "Titulo"=>"Ocurrio un error inesperado",
				    "Texto"=>"Ha ingresado un correo no valido",
				    "icon"=>"error"
			    ];

			    echo json_decode($alerta);
			    exit;
			}
			
		}

		/*-----comprobando contraseña sean iguales----*/

		if ($clave1!=$clave2) {

			$alerta=[

				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"Las CLAVES ingresadas no coinciden",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;

		} else {
			$clave=modeloPrincipal::encryption($clave1);
		}
		
		/*-----comprobando privilegios----*/

		if ($privilegio<1 || $privilegio>3) {

			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El privilegio seleccionado no es valido",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
		}

		$datos_usuario_reg=[
			"DNI"=>$dni,
			"nombre"=>$nombre,
			"apellido"=>$apellido,
			"telefono"=>$telefono,
			"direccion"=>$direccion,
			"email"=>$email,
			"usuario"=>$usuario,
			"clave"=>$clave,
			"estado"=>"Activa",
			"privilegio"=>$privilegio
		];

		$agregar_usuario=modeloUsuario::agregar_usuario_modelo($datos_usuario_reg);

		if ($agregar_usuario->rowCount()==1) {
			$alerta=[
				"Alerta"=>"limpiar",
				"Titulo"=>"USUARIO REGISTRADO",
				"Texto"=>"Los datos del usuario han sido registrado con exito",
				"icon"=>"success"
			];
		
		} else {
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido registrar el usuario",
				"icon"=>"error"
			];
		}

		echo json_decode($alerta);			
		
	} /*------ FIN DEL CONTROLADOR------ */

	/*-----controlador para paginar usuarios----*/

	public function paginador_usuario_controlador($pagina, $registros,$privilegio,$id,$url,$busqueda)	{

		$pagina=modeloPrincipal::limpiar_cadena($pagina);
		$registros=modeloPrincipal::limpiar_cadena($registros);
		$privilegio=modeloPrincipal::limpiar_cadena($privilegio);
		$id=modeloPrincipal::limpiar_cadena($id);
		
		$url=modeloPrincipal::limpiar_cadena($url);
		$url=SERVERURL.$url."/";

		$busqueda=modeloPrincipal::limpiar_cadena($busqueda);

		$tabla="";

		$pagina= (isset($pagina) && $pagina>0) ? (int)$pagina: 1;
		$inicio= ($pagina>0) ? (($pagina*$registros)-$registros) : 0;

		if (isset($busqueda && $busqueda!='')) {
			$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuarios WHERE ((usuario_id!='$id' AND usuario_id!='1') AND (usuario_dni LIKE '%$busqueda%' OR usuario_nombre LIKE '%$busqueda%' OR usuario_apellido LIKE '%$busqueda%' OR usuario_telefono LIKE '%$busqueda%' OR usuario_correo LIKE '%$busqueda%' OR usuario_usuario LIKE '%$busqueda%')) ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";
		} else {
			$consulta="SELECT SQL_CALC_FOUND_ROWS * FROM usuarios WHERE usuario_id!='$id' AND usuario_id!='1' ORDER BY usuario_nombre ASC LIMIT $inicio,$registros";
		}
		
		$conexion=modeloPrincipal::conectar();

		$datos=$conexion->query($consulta);
		$datos=$datos->fetchAll();

		$total=$conexion->query("SELECT FOUND_ROWS()");
		$total= (int)$total->fetchColumn();

		$Npaginas=ceil($total/$registros);

		$tabla.= '<div class="table-responsive">
		<table class="table table-dark table-sm">
			<thead>
				<tr class="text-center roboto-medium">
					<th>#</th>
					<th>DNI</th>
					<th>NOMBRE</th>
					<th>APELLIDO</th>
					<th>TELÉFONO</th>
					<th>USUARIO</th>
					<th>EMAIL</th>
					<th>ACTUALIZAR</th>
					<th>ELIMINAR</th>
				</tr>
			</thead>
			<tbody>';

		if ($total>=1 && $pagina<=$Npaginas ) {
			$contador=$inicio+1;
			$reg_inicio=$inicio+1;
			foreach ($datos as $rows) {
				$tabla.='<tr class="text-center" >
					<td>'.$contador.'</td>
					<td>'.$rows['usuario_dni'].'</td>
					<td>'.$rows['usuario_nombre'].'</td>
					<td>'.$rows['usuario_apellido'].'</td>
					<td>'.$rows['usuario_telefono'].'</td>
					<td>'.$rows['usuario_usuario'].'</td>
					<td>'.$rows['usuario_email'].'</td>
					<td>
						<a href="'.SERVERURL.'user-update/'.modeloPrincipal::encryption($rows['usuario_id']).'/" class="btn btn-success">
								<i class="fas fa-sync-alt"></i>	
						</a>
					</td>
					<td>
						<form class="FormularioAjax" action="'.SERVERURL.'ajax/usuarioAjax.php" method="POST" data-form="eliminar" autocomplete="off">
							<input type="hidden" name="usuario_id_delete" value="'.modeloPrincipal::encryption($rows['usuario_id']).'">
							<button type="submit" class="btn btn-warning">
									<i class="far fa-trash-alt"></i>
							</button>
						</form>
					</td>
				</tr>';
				$contador++;
			}
			$reg_final=$contador-1;
		} else {
			if ($total>=1) {
				$tabla.='<tr class="text-center" ><td colspan="9">
				<a href="'.$url.'" class="btn btn-aised btn-primary btn-sm">Haga click aca para recargar el listado</a>
				</td></tr>';

			} else {
				$tabla.='<tr class="text-center" ><td colspan="9">No hay registros en el sistema</td></tr>';
			}
			
			$tabla.='<tr class="text-center" ><td colspan="9">No hay registros en el sistema</td></tr>';
		}
		

		$tabla.= '</tbody></table></div>';

		

	    if ($total>=1 && $pagina<=$Npaginas) {
	        $tabla.= '<p class="text-rigth">Mostrando usuario'.$reg_inicio.' al '.$reg_final.' de un total de '.$total.'</p>';
	        $tabla.=modeloPrincipal::paginador_tablas($paginas, $Npaginas, $url, 7);
	    } 	        

	    return $tabla;

		
	}/*------ FIN DEL CONTROLADOR------ */

	/*-----controlador para eliminar usuarios----*/

	public function eliminar_usuario_controlador(){
		/*--Recibiendo el id del usuario-- */

		$id = modeloPrincipal::decryption($_POST['usuario_id_delete']);
		$id = modeloPrincipal::limpiar_cadena($id);

		/*--Comprobando el usuario principal-- */

		if ($id==1) {
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No podemos eliminar el usuario principal de sistema",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
		} 


		/*--Comprobando el usuario en la BD-- */
		
		$check_usuario=modeloPrincipal::ejecutar_consultas_simples("SELECT usuario_id FROM usuarios WHERE usuario_id='$id'");

		if ($check_usuario->rowCount()<=0) {
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El usuario que intenta eliminar no existe sistema",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
		}

		/*--Comprobando los prestamos del usuario-- */
		
		$check_prestamos=modeloPrincipal::ejecutar_consultas_simples("SELECT usuario_id FROM prestamo WHERE usuario_id='$id' LIMIT 1");

		if ($check_prestamos->rowCount()>0) {
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"El usuario no se puede eliminar, debido a que tiene prestamos asociados, recomendamos deshabilitar el ususario del sistema si no se va a utilizar",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
		}

		/*--Comprobando los privilegios-- */

		session_start(['name'=>'SPM']);

		if ($_SESSION['privilegio_spm'] != 1) {
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No tienes los permisos necesarios para realizar esta operacion",
				"icon"=>"error"
			];

			echo json_decode($alerta);
			exit;
		}

		$eliminar_usuario = modeloUsuario::eliminar_usuario_modelo($id);

		if ($eliminar_usuario->rowCount()==1) {
			$alerta=[
				"Alerta"=>"recargar",
				"Titulo"=>"Usuario eliminado",
				"Texto"=>"El usuario ha sido eliminado del sistema exitosamente",
				"icon"=>"success"
			];
		
		} else {
			$alerta=[
				"Alerta"=>"simple",
				"Titulo"=>"Ocurrio un error inesperado",
				"Texto"=>"No hemos podido eliminar el usuario, por favor intente nuevamente",
				"icon"=>"error"
			];
		}

		echo json_decode($alerta);
		

	}/*------ FIN DEL CONTROLADOR------ */


}
