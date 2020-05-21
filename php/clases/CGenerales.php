<?php 
include_once("../../librerias/php/JSON.php");
include_once("../objetos/usuarios.php");
include_once("../clases/CLog.php");
class CGenerales
{	
	function iniciarSesion($conexMySql,$pEmail,$pPassword)
	{
		CLog::escribirLog(":: PROJVUE :: CGenerales :: iniciarSesion([$pEmail],[$pPassword]); ::");

		$datos = array();
		$datos['mensaje'] = "";
		$datos['respuesta'] = 0;
		$datos['resultOper'] = null;

		if ($pEmail != "" && $pPassword != "") 
		{
			try 
			{
				$cSql="SELECT id,nombre,apellido,email,password FROM  usuarios where email = '$pEmail' AND password = '$pPassword';";

				$Usuario = new Usuario;
				
				foreach ($conexMySql->query($cSql) as $Resultado) 
				{
					$Usuario->setId($Resultado['id']);
					$Usuario->setNombre($Resultado['nombre']);
					$Usuario->setApellido($Resultado['apellido']);
					$Usuario->setEmail($Resultado['email']);
					$Usuario->setPassword($Resultado['password']);

				}
					if ($Usuario->getId() != 0 && $Usuario->getId() != null) 
					{
						$datos['mensaje'] = "Bienvenido!.";
						$datos['respuesta'] = $Usuario;
						$datos['resultOper'] = 1;
					}
					else
					{
						$datos['respuesta'] = $Usuario;
						$datos['mensaje'] = "Datos Incorrectos!.";
						$datos['resultOper'] = 2;
					}
			} catch (Exception $e) {
				$datos['mensaje'] = $e;
				$datos['resultOper'] = -1;
			}
			
			CLog::escribirLog(":: PROJVUE :: CGenerales :: Sql = $cSql");
		}
		return $datos;		

	}

	function obtenerUsuarios($conexMySql)
	{
		$datos = array();
		$datos['mensaje'] = null;
		$datos['CodRespuesta'] = 0;
		$datos['infoResponse']=array();
		
		
			$cSql="SELECT * FROM  usuarios;";
			foreach ($conexMySql->query($cSql) as $Resultado) 
			{
				$Usuario = new Usuario;
				$Usuario->setId($Resultado['id']);
				$Usuario->setNombre($Resultado['nombre']);
				$Usuario->setApellido($Resultado['apellido']);
				$Usuario->setEmail($Resultado['email']);
				$Usuario->setPassword($Resultado['password']);

				$datos['infoResponse'][]=$Usuario;
			}
			$datos['mensaje'] = "Informacion obtenida con exito!";
			$datos['CodRespuesta'] = 1;
		

		return $datos;		
	}
	function clientConsultEstServSOAP($p_tipoServicio,$p_folio,$p_nss,$p_curp,$p_origenConsumo)
	{
		CLog::escribirLog("::CGenerales.clientConsultEstServSOAP:: inicia consumo");

		$datos = array();
		$datos['resultOper'] = "";
		$datos['descOper'] = "";
		$datos['response'] = "";

		$client = null;	
		//URL de webservice	
		$soapUrl ='http://10.44.172.69:8185/cxf/wsconsultaestatusservicio?wsdl';

		CLog::escribirLog("::CGenerales.clientConsultEstServSOAP:: validacion de datos");

		if ($p_tipoServicio != "" && $p_folio != "" && ($p_nss != "" || $p_curp != "")) {
			# code...
		
			$param = array('tipoServicio' => $p_tipoServicio,
						   'folioServicio' => $p_folio,
						   'curp' => $p_curp,
						   'nss' => $p_nss);

			CLog::escribirLog("::CGenerales.clientConsultEstServSOAP:: parametros: " . 
			'tipoServicio:' . $p_tipoServicio . ',folioServicio:' . $p_folio. ',curp:' . $p_curp. ',nss:' . $p_nss);

			$xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
                            <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:con="http://wwww.aforecoppel.com.mx/Consultas/ConsultaEstatusServicio/consultarEstatusServicio/">
							   <soapenv:Header/>
							   <soapenv:Body>
							      <con:consultarEstatusRequest>
							         <cuerpo>
							            <tipoServicio>'. $p_tipoServicio .'</tipoServicio>
							            <folioServicio>'. $p_folio .'</folioServicio>
							            <!--Optional:-->
							            <curp>'. $p_curp .'</curp>
							            <!--Optional:-->
							            <nss>'. $p_nss .'</nss>
						            	<origen>'. $p_origenConsumo .'</origen>
							         </cuerpo>
							      </con:consultarEstatusRequest>
							   </soapenv:Body>
							</soapenv:Envelope>';   // data from the form, e.g. some ID number

           $headers = array(
                        "Content-type: text/xml;charset=\"utf-8\"",
                        "Accept: text/xml",
                        "Cache-Control: no-cache",
                        "Pragma: no-cache",
                        "SOAPAction: ", 
                        "Content-length: ".strlen($xml_post_string),
                    ); //SOAPAction: your op URL

			$ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($ch, CURLOPT_URL, $soapUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch); 
            curl_close($ch);
          
 			$removSuperior = str_replace('<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><ns2:consultarEstatusServicioRespuesta xmlns:ns2="http://wwww.aforecoppel.com.mx/Consultas/ConsultaEstatusServicio/consultarEstatusServicio/">','',$response);
            $removInferior = str_replace('</ns2:consultarEstatusServicioRespuesta></soap:Body></soap:Envelope>','',$removSuperior);

            $objetoRespuesta = simplexml_load_string($removInferior);
			
			$datos['resultOper'] = 1;
			$datos['response'] = $objetoRespuesta;
			$datos['descOper'] = "EXITO";
		}
		else
		{
			CLog::escribirLog("::CGenerales.clientConsultEstServSOAP:: Campos Obligatorios Faltantes");
			$datos['resultOper'] = 2;
			$datos['descOper'] = "Campos Obligatorios Faltantes";
		}

		CLog::escribirLog("::CGenerales.clientConsultEstServSOAP:: Finaliza Consumo");

		return $datos;
	}
	
}
 ?>