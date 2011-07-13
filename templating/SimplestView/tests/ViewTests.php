<?php
PHPUnit_Util_Filter::addFileToFilter( __FILE__, 'TEST_CASE' );

require_once 'tests/data/guestbook.php';
require_once 'library/View.php';

class ViewTests extends PHPUnit_Framework_TestCase {
  public function testLoad() {
    View::load( 'views/guestbook-entry.phtml' );
    self::assertTrue( true );
  }
  public function testLoadBadFileName() {
    $this->setExpectedException( 'ViewBadFileNameException' );
    View::load( 'foo/bar' );
  }
  public function testRenderArray() {
    View::registerForModel( 'GuestbookEntry',
    	'views/guestbook-entry.phtml' );
    $entry = array(
      "title" => "title",
      "address" => "address",
      "message" => "message" );
    self::assertEquals(
 '<div class="guestbook-entry">
  <h3>title</h3>
  <address>address</address>
  <p>message</p>
</div>
', View::render( $entry, 'GuestbookEntry' ) );
  }
  public function testRenderObject() {
    View::registerForModel( 'GuestbookEntry',
      'views/guestbook-entry.phtml' );
    $entry = (object) array(
      "title" => "title",
      "address" => "address",
      "message" => "message" );
    self::assertEquals(
 '<div class="guestbook-entry">
  <h3>title</h3>
  <address>address</address>
  <p>message</p>
</div>
', View::render( $entry, 'GuestbookEntry' ) );
  }
  public function testRenderObjectException() {
    $this->setExpectedException( 'ViewUnregisteredModelException' );
    $entry = (object) array(
      "title" => "title",
      "address" => "address",
      "message" => "message" );
    self::assertEquals(
 '<div class="guestbook-entry">
  <h3>title</h3>
  <address>address</address>
  <p>message</p>
</div>
', View::render( $entry ) );
  }
  public function testRenderModelInstance() {
    View::registerForModel( 'GuestbookEntry',
      'views/guestbook-entry.phtml' );
    $entry = new GuestbookEntry( "title", "address", "message" );
    self::assertEquals(
 '<div class="guestbook-entry">
  <h3>title</h3>
  <address>address</address>
  <p>message</p>
</div>
', View::render( $entry ) );
  }
}
