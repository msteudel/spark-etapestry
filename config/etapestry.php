<?php
/**
 * Created for Alliance v3.
 * User: msteudel
 * Date: 5/30/14
 * Time: 3:57 PM
 */

$config['environment'] = 'dev';

switch( $_SERVER['HTTP_HOST'] ) {
	case "DEV_URL":
		$config['environment'] = 'dev';
		break;
	case "PROD_URL":
		$config['environment'] = 'prod';
		break;
}

$config['dev_etap_login_id']= '';
$config['dev_etap_password']= '';

$config['prod_etap_login_id']= '';
$config['prod_etap_password']= '';

$config['etap_endpoint'] = "https://sna.etapestry.com/v2messaging/service?WSDL";