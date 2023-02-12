<?php
//Validación del lado del servidor
if (isset($_POST['crear'])){
	if (empty($_POST['subdominio'])) {//Verifica q el campo no esté vacío
            $errors = "Ingrese el subdominio";
        }  elseif (strlen($_POST['subdominio']) > 20 || strlen($_POST['subdominio']) < 2) {//Verifica q el campo contenga de 2 a 20 caracteres
            $errors = "El subdominio no puede ser inferior a 2 o más de 20 caracteres";
        } elseif (!preg_match('/^[a-zd]{2,20}$/i', $_POST['subdominio'])) {//Verifica que solo contenga caracteres alfa numéricos de 2 a 20 caracteres
            $errors = "El subdominio no permitido: Sólo están permitidos  de 2 a 20 caracteres alfa numéricos";
        } else if (!empty($_POST['subdominio'])){//Sino esta vacío entonces

			$subdominio=strtolower($_POST['subdominio']);//Convierto a minisculas
			$domain='';//nombre de tu dominio
			/*------------------------ SubDomain Create ---------------*/
			  require("xmlapi.php");//Llamar la clase xmlapi

			  $cpanelusr = '';//nombre del usuario cPanel
			  $cpanelpass = '';//Contraseña del usuario cPanel
			  $xmlapi = new xmlapi('127.0.0.1');//Instanciamos la clase xmlapi pasando como parametro 127.0.0.1 
			  $xmlapi->set_port( 2082 );//Puerto cPanel puede ser 2082 ó 2083
			  $xmlapi->password_auth($cpanelusr,$cpanelpass);//Autenticacion en cPanel
			  $xmlapi->set_debug(1); //Salida de errores 1= verdadero
			  $json=$xmlapi->set_output('json');//Convierte mensajes de la api en formato json
			 
			 $result = $xmlapi->api1_query($cpanelusr, 'SubDomain', 'addsubdomain', array($subdominio,$domain,0,0, '/public_html/'.$subdominio));//Creamos el subdominio
				 
				$array = json_decode($result);//Convierte en un array los datos json enviados por la API
				$errors_api= $array->{'error'}; //Extrae el mensaje de Mensaje de error

				if ($errors_api==null)
				{
			  if ($result){
				  $messages="El dominio <strong>$subdominio.$domain</strong> ha sido creado con éxito.";
				  
			  } else {
				  $errors="No se pudo crear el subdominio.";
			  }
				}
				else {
					$errors=$errors_api;
				}
			/*------------------------ fin SubDomain Create ---------------*/
		}
		else {
			$errors='Error desconocido';
		}
}
?>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Crear Subdominios en  cPanel usando PHP</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
	<style>
		body {
			margin: 80px auto;
			width: 500px;
			text-align: center;
		}
	</style>
</head>
<body>
	<h2>Crear Subdominios en  cPanel usando PHP</h2>
	<?php 
		if ($errors){
			?>
			<div class="alert alert-danger alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Error!</strong> <?php echo $errors;?>
			</div>
			<?php
		}
	?>
	<?php 
		if ($messages){
			?>
			<div class="alert alert-success alert-dismissible" role="alert">
			  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  <strong>Bien hecho!</strong> <?php echo $messages;?>
			</div>
			<?php
		}
	?>
				<form method='post' class="navbar-form" role="form">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
                            <input id="subdominio" type="text" class="form-control" name="subdominio" value="" placeholder="Subdominio" pattern="[a-zA-Z0-9]{2,20}"  required title='Sólo se admiten de 2 a 20 caracteres alfa numéricos'>                                        
							
						</div> .<?php echo $_SERVER['SERVER_NAME'];?>


   

                        <button type="submit" class="btn btn-primary" name="crear">Crear</button>
                  </form>
</body>
</html>