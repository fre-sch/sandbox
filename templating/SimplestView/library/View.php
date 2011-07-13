<?php

class ViewBadFileNameException extends Exception {
  public function __construct( $file_name ) {
    parent::__construct(
      "file '$file_name' not found or not readable" );
  }
}
class ViewUnregisteredModelException extends Exception {
  public function __construct( $model_name = '' ) {
    if ( empty( $model_name ) )
      $model_name = 'empty';
    parent::__construct( "no view for model '$model_name'" );
  }
}
class ViewTypeException extends Exception {
  public function __construct( $var, $types = '' ) {
    if ( is_object( $var ) )
      $type = get_class( $var );
    else
      $type = gettype( $var );
    parent::__construct(
      "wrong type '$type',  $types expected." );
  }
}

/**
 * Load view templates and render models
 *
 * @author frederik
 */
class View {
  public static $create_funcs = true;
  /**
   * @var string
   */
  public static $encoding = "UTF-8";
  /**
   * @var array
   */
  private static $loaded = array();
  /**
   * @var array
   */
  private static $registered = array();
  /**
   * Load/reload view template file.
   *
   * @throws ViewBadFileNameException
   * @param string $file_name
   * @param bool $reload
   * @return function
   */
  public static function load( $file_name, $reload = false ) {
    if ( !isset( self::$loaded[ $file_name ] ) || $reload ) {
      $view_contents = @file_get_contents( $file_name, FILE_USE_INCLUDE_PATH );
      if ( $view_contents === false )
        throw new ViewBadFileNameException( $file_name );
      self::$loaded[ $file_name ] = create_function( '',
        "extract(func_get_arg(0));ob_start();?>$view_contents<?php return ob_get_clean();" );
    }
    return self::$loaded[ $file_name ];
  }
  /**
   * Register a view template for a model
   * @param string $model_name
   * @param string $file_name
   */
  public static function registerForModel( $model_name, $file_name ) {
    self::$registered[ $model_name ] = $file_name;
  }
  /**
   * Test include'ing instead of loading and creating function.
   */
  private static function includeAndRender() {
    extract( func_get_arg( 1 ) );
    ob_start();
    $success = @include func_get_arg( 0 );
    if ( $success === false ) {
      ob_end_clean();
      throw new ViewBadFileNameException( func_get_arg( 0 ) );
    }
    return ob_get_clean();
  }
  /**
   * Render Model with optional view.
   *
   * @throws ViewUnregisteredModelException
   * @param object $model
   * @param string $model_name
   * @return string
   */
  public static function render( $model, $model_name = '' ) {
    // get model name and context
    if ( is_object( $model ) ) {
      if ( empty( $model_name ) )
        $model_name = get_class( $model );
      $context = get_object_vars( $model );
    }
    elseif ( is_array( $model ) || $model instanceof ArrayAccess ) {
      if ( empty( $model_name ) )
        throw new ViewUnregisteredModelException();
      $context = $model;
    }
    else {
      throw new ViewTypeException( $model, 'object, array' );
    }

    // get file name and render
    $file_name = self::$registered[ $model_name ];
    if ( $file_name === null )
      throw new ViewUnregisteredModelException( $model_name );
    if ( self::$create_funcs ) {
      $view_func = self::load( $file_name );
      return $view_func( $context );
    }
    else {
      return self::includeAndRender( $file_name, $context );
    }
  }
  /**
   * Escape HTML special characters
   *
   * @param string $str_val
   * @return string
   */
  public static function escape( $str_val ) {
    return htmlspecialchars( (string)$str_val, ENT_QUOTES, self::$encoding );
  }
  /**
   * @param mixed $input
   */
  public static function date( $input ) {
    if ( empty( $input ) ) {
      $timestamp = time();
    }
    elseif ( is_numeric( $input ) ) {
      $timestamp = (int)$input;
    }
    else {
      $timestamp = strtotime( $input );
    }
    return date( "D, d M Y - H:i:s", $timestamp );
  }
}
