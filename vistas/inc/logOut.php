<script type="text/javascript">
	let btnSalir=document.querySelector(".btn-exit-system");
	btnSalir.addEventListener('click', function (e) {
		e.preventDefault();
		Swal.fire({
			title: '¿Estás seguro de cerrar la sesión?',
			text: "Está a punto de cerrar la sesión y salir del sistema.",
			icon: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'SI, salir!',
			cancelButtonText: 'No, cancelar'
		}).then((result) => {
			if (result.value) {
				let url='<<?php echo SERVERURL;?>ajax/loginAjax.php';
				let token='<<?php echo $lc->encryption($_SESSION['token_spm'])?>';
				let usuario='<<?php echo $lc->encryption($_SESSION['usuario_spm'])?>';  

				let datos= new FormData();
				datos.append("token",token);
				datos.append("usuario",usuario);

				fetch(url,{
					method:'POST',
					body:datos
				});
				.then(respuesta => respuesta.json());
				.then(respuesta => {
				return alertas_ajax(respuesta);

			});
		}
	})
</script>