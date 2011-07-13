<?php
require_once '../setup.php';
require_once 'guestbook.php';
require_once 'library/View.php';

View::$create_funcs = isset( $_GET[ "create_funcs" ] );
View::registerForModel( "Layout", "views/layout.phtml" );
View::registerForModel( 'User', 'views/user.phtml' );
View::registerForModel( 'Guestbook', 'views/guestbook.phtml' );
View::registerForModel( 'GuestbookEntry', 'views/guestbook-entry.phtml' );
echo View::render( array(
  "title" => "Profile Test",
  "content" => Guestbook::getTestInstance( 100 )
), "Layout" );
