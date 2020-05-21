<?php 
	include_once('../clases/CGenerales.php');
	include_once("../../librerias/php/JSON.PHP");
	include_once("../clases/CConexionesDB.php");
	include_once("../DatosGlobales/ConstantesGlobales.php");
	include_once("../clases/CLog.php");
	include_once("../objetos/usuarios.php");
	$datosRespuesta = array();
	$Usuario = new Usuario();

	//obtencion de peticiones
	
	$opcion  = isset($_GET['opcion']) ? $_GET['opcion'] : 0;

		/*datos Login INICIO*/
		$user  = isset($_GET['email']) ? $Usuario->setEmail($_GET['email']) : $Usuario->setEmail("");
		$pass  = isset($_GET['password']) ? $Usuario->setPassword($_GET['password']) : $Usuario->setPassword("");
		/*datos Login fin*/

		/*datos consumo servicio INICIO*/
		$tipoServicioConsumo = isset($_GET['tipoServicio']) ? $_GET['tipoServicio'] : "";
		$folioConsumo        = isset($_GET['folio']) ? $_GET['folio'] : "";
		$nssConsumo          = isset($_GET['nss']) ? $_GET['nss'] : "";
		$curpConsumo         = isset($_GET['curp']) ? $_GET['curp'] : "";
		$origenConsumo       = isset($_GET['origen']) ? $_GET['origen'] : "";
		/*datos consumo servicio FIN*/

	ini_set('memory_limit', '-1');
	set_time_limit(0);	
	//ESTAS DOS LINEAS ES PARA RESOLVER EL PROBLEMA DE LAS Ñ
	setlocale(LC_ALL,'es_ES'); 
	define("CHARSET", "iso-8859-1");

	//ABRIR CONEXION GETGRESQL
	/**/
	
	//ABRIR CONEXION MYSQL
	$conexMySql = CConexionesDB::cnxMySql("prueba");

	switch($opcion) 
	{
		case 1:
			$datosRespuesta = CGenerales::iniciarSesion($conexMySql,$Usuario->getEmail(),$Usuario->getPassword());
			CLog::escribirLog("PROJVUE :: [CGenerales::iniciarSesion] :: finaliza inicio de sesion");
			echo json_encode($datosRespuesta);
			break;
		case 2:
			$datosRespuesta = CGenerales::obtenerUsuarios($conexMySql);
			echo json_encode($datosRespuesta);
			break;
		case 3:
			$datosRespuesta = CGenerales::clientConsultEstServSOAP($tipoServicioConsumo,$folioConsumo,$nssConsumo,$curpConsumo,$origenConsumo);
			echo json_encode($datosRespuesta);
			break;
		case 0:
			echo "Opcion Invalida: " . $opcion;
			break;
	}
 ?>