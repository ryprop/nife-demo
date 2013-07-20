<?php

class NifeDemo_Dispatcher
{
	protected $config;
	public function __construct( NifeDemo_Config $config ) {
		$this->config = $config;
	}
	
	public function handleRequest( $path ) {
		return Nife_Util::httpResponse( 200, "You requested $path!" );
	}
}
