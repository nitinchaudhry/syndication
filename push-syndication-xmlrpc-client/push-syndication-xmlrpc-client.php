<?php
/*

**************************************************************************

Plugin Name:  Push Syndication XMLRPC Client
Plugin URI:
Description:  XMLRPC client for push syndication server
Version:      1.0
Author:       Automattic
Author URI:   http://automattic.com/wordpress-plugins/
License:      GPLv2 or later

**************************************************************************/

class Push_Syndication_XMLRPC_Client {

	function __construct() {
		add_filter( 'xmlrpc_methods' , array( &$this, 'push_syndicate_methods' ) );
	}

	public function push_syndicate_methods( $methods ) {
		$methods['pushSyndicateSetOption']          = array( &$this, 'push_syndicate_set_option' );
		return $methods;
	}

	public function push_syndicate_set_option( $args ) {

		global $wp_xmlrpc_server;
		$wp_xmlrpc_server->escape($args);

		$blog_id	= (int) $args[0];
		$username	= $args[1];
		$password	= $args[2];
		$options	= (array) $args[3];

		if ( !$user = $this->login($username, $password) )
			return $this->error;

		if ( !current_user_can( 'manage_options' ) )
			return new IXR_Error( 403, __( 'You are not allowed to update options.' ) );

		foreach( $options as $key => $value ) {
			// @TODO validation
			// @TODO acc errors
			if( $key[0] == '_' )
				continue;
			update_option( $key, $value );
		}

	}

}

$Push_Syndication_XMLRPC_Client = new Push_Syndication_XMLRPC_Client();