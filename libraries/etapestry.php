<?php if ( !defined( 'BASEPATH' ) ) {
	exit( 'No direct script access allowed' );
}

class Etapestry {

	/**
	 * @var object
	 * @access private
	 */
	private $_ci;
	private $_login;
	private $_password;
	private $_endpoint;
	private $_nsc;

	public function __construct( $auto_login = true ) {
		$this->_ci       =& get_instance();
		$this->_endpoint = config_item( 'etap_endpoint' );
		$this->_nsc      = new nusoap_client( $this->_endpoint, true );

		if ( $auto_login ) {
			$this->login();
		}
	}

	/**
	 * Logs user into eTap REST API
	 *
	 * @access public
	 */
	public function login() {
		$env = config_item( 'environment' );

		if ( $env == 'dev' ) {
			$this->_login    = config_item( 'dev_etap_login_id' );
			$this->_password = config_item( 'dev_etap_password' );
		} else {
			$this->_login    = config_item( 'prod_etap_login_id' );
			$this->_password = config_item( 'prod_etap_password' );
		}

		$creds = array( $this->_login, $this->_password );

		$newEndpoint = $this->_nsc->call( "login", $creds );
		$this->checkStatus( $this->_nsc );
	}

	/**
	 * Logs user out eTap REST API
	 *
	 * @access public
	 */
	public function logout() {
		$this->_nsc->call( "logout" );
	}

	/**
	 * Gets a duplicate account
	 *
	 * @param string $params
	 */
	public function getDuplicateAccount( $email ) {
		$account = array();

		if ( !empty( $email ) ) {
			$params['email']               = $email;
			$params["allowEmailOnlyMatch"] = true;
			$account                       = $this->_nsc->call( "getDuplicateAccount", array( $params ) );
			$this->checkStatus( $this->_nsc );
		}

		return $account;
	}

	/**
	 * Gets all duplicate accounts that match an email
	 *
	 * @param string $params
	 */
	public function getDuplicateAccounts( $email ) {
		$account = array();

		if ( !empty( $email ) ) {
			$params['email']               = $email;
			$params["allowEmailOnlyMatch"] = true;
			$account                       = $this->_nsc->call( "getDuplicateAccounts", array( $params ) );
			$this->checkStatus( $this->_nsc );
		}

		return $account;
	}

	/**
	 * @param array $account
	 *
	 * @return bool|mixed
	 * @throws Exception
	 */
	public function addAccount( array $account ) {
		if ( empty( $account['email'] ) ) {
			throw new Exception( 'Email required' );
		}
		$response          = false;
		$duplicate_account = $this->getDuplicateAccount( $account['email'] );

		if ( empty( $duplicate_account ) ) {
			$dv1 = array();
			$dv1["fieldName"] = "Alliance Connect Flag";
			$dv1["value"] = "Alliance Connect Flag";
			$account["accountDefinedValues"] = array($dv1);

			$response = $this->_nsc->call( "addAccount", array( $account, false ) );
			$this->checkStatus( $this->_nsc );
		}
		else {
			el( 'duplicate accounts' );
		}

		return $response;
	}

	/**
	 * @param array $account
	 * @param array $values
	 *
	 * @throws Exception
	 */
	public function updateAccount( $account ) {
		$response = $this->_nsc->call( "updateAccount", array( $account, false ) );
		$this->checkStatus( $this->_nsc );

		return $response;
	}

	/**
	 * @param string $email
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function getAccount( $email ) {
		$account = $this->_nsc->call( "getAccount", array( $email ) );

		$this->checkStatus( $this->_nsc );

		return $account;
	}

	public function getUserDefinedSetValues( $fieldName, $getDisabled = false )
	{
		$account = $this->_nsc->call( "getUserDefinedSetValues", array( $fieldName, $getDisabled ) );

		displayArray( $account );
	}

	/**
	 * Utility method to determine if a NuSoap fault or error occurred.
	 * If so, output any relevant info and stop the code from executing.
	 *
	 * @param $nsc obj NuSoapClient
	 *
	 * @throws Exception
	 */
	function checkStatus( $nsc ) {
		if ( $nsc->fault || $nsc->getError() ) {
			if ( !$nsc->fault ) {
				throw new Exception( "Error: " . $nsc->getError() );
			} else {
				throw new Exception( "Fault Code: " . $nsc->faultcode . "<br>" . "Fault String: " . $nsc->faultstring . "<br>" );
			}
			exit;
		}
	}

	/**
	 * @param $connect_data
	 * @param $etap_data
	 *
	 * takes the connect user data and updates the etap user data
	 */
	public function map_connect_to_etap( $connect_data, $etap_data )
	{
		$etap_data['firstName'] = $connect_data['first_name'];
		$etap_data['lastName'] = $connect_data['last_name'];
		$etap_data['name'] = $connect_data['first_name'] . ' ' . $connect_data['last_name'];
		$etap_data['sortName'] = $connect_data['last_name'] . ', ' . $connect_data['first_name'];
		$etap_data['email'] = $connect_data['email'];
		$etap_data['address'] = $connect_data['address_1'] . ( !empty( $etap_data['address_2'] ) ? ' ' . $etap_data['address_2'] : '' );
		$etap_data['city'] = $connect_data['city'];
		$etap_data['state'] = $connect_data['state'];
		$etap_data['postalCode'] = $connect_data['zip'];

		return $etap_data;
	}
}
