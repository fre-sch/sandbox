<?php
/**
 * @package Reflex
 */

/**
 * Static initialization and class loading.
 */
class Reflex_Library {
  const NAMESPACE = "Reflex";
  /**
   * Loads $class if it begins with NAMESPACE.
   * @param string $class
   */
  public static function autoload( $class ) {
    if ( strpos( $class, self::NAMESPACE."_" ) === 0 ) {
      $fileName = str_replace( "_", "/", $class );
      include dirname( __FILE__ ) . "/{$fileName}.php";
    }
  }
  /**
   * Register the autoload function. Do not call this function, it will be
   * called when you include this file.
   *
   * @author frederik
   * @since 28.06.2009
   */
  public static function register() {
    $autoloaders = (array) spl_autoload_functions();
    if ( !in_array( __CLASS__ . "::autoload", $autoloaders ) ) {
      spl_autoload_register( __CLASS__ . "::autoload" );
    }
  }
}

Reflex_Library::register();
