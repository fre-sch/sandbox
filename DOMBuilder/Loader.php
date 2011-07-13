<?php
require_once dirname( __FILE__ ) . '/DOMBuilder.php';
/**
 * Static initialization and class loading.
 */
class DOMBuilder_Loader {
	/**
	 * Namespace/prefix for autoload function
	 */
	const NS = 'DOMBuilder';
	/**
	 * @var string Absolute path to library
	 */
	private static $directory;
	/**
	 * Loads $class if it begins with DOMBuilder::NS.
	 * @param string $class
	 */
	public static function autoload( $class ) {
		if ( strpos( $class, self::NS . '_' ) === 0 ) {
			include self::$directory
				. '/' . str_replace( '_', '/', $class ) . '.php';
		}
	}
	/**
	 * Register autoload function.
	 */
	public static function register() {
		$autoloaders = (array) spl_autoload_functions();
		$loaderName =  __CLASS__ . '::autoload';
		if ( !in_array( $loaderName, $autoloaders ) ) {
			self::$directory = dirname( __FILE__ );
			spl_autoload_register( $loaderName );
		}
	}
}
DOMBuilder_Loader::register();
