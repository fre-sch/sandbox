<?php

$setup_file = dirname( __FILE__ ) . "/setup.php";
require_once $setup_file;
PHPUnit_Util_Filter::addFileToFilter( $setup_file, 'TEST_CASE' );
PHPUnit_Util_Filter::addFileToFilter( __FILE__, 'TEST_CASE' );

class AllTests extends PHPUnit_Framework_TestSuite {

  public function __construct() {
    $this->setName( 'All Tests' );
    $base_dir = dirname( __FILE__ );
    $this->addTestFiles( array(
      "$base_dir/ViewTests.php",
    ) );
  }
  public static function suite() {
    return new self( );
  }

}

