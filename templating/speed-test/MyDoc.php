<?php
//define( '__DIR__', dirname( __FILE__ ) );

class MyDoc extends DOMDocument {
	/**
	 * @var MyDoc
	 */
	private static $instance;
	/**
	 * @var DOMXPath
	 */
	private $xpath;
	public function __construct() {
		parent::__construct();
		$this->loadHTMLFile( __DIR__ . '/template-dom.html' );
		$this->xpath = new DOMXPath( $this );
	}
	public function query( $query ) {
		return $this->xpath->query( $query );
	}
	/**
	 * @return MyDoc
	 */
	public static function getInstance() {
		if ( !self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}
