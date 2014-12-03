<?php
/**
 * Created for Alliance v3.
 * User: msteudel
 * Date: 5/30/14
 * Time: 3:58 PM
 */


$autoload['config'] = array( 'etapestry');

# Load the birdseed helper when the spark is loaded
$autoload['libraries'] = array('nusoap_base','etapestry');

$autoload['helper'] = array('etapestry_helper');