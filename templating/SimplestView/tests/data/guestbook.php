<?php
class User {
  public $name;
  public $address;
  public function __construct( $name, $address ) {
    $this->name = $name;
    $this->address = $address;
  }
  public static function getTestInstance() {
    $name = "";
    for ( $i = 0, $n = 6; $i < $n; ++$i )
      $name .= chr( rand( 97, 97 + 26 ) );
    return new self( $name, "$name@foo.bar" );
  }
}

class GuestbookEntry {
  public $title;
  public $user;
  public $message;
  public $date;
  public function __construct( $title, $user, $message, $date ) {
    $this->title = $title;
    $this->user = $user;
    $this->message = $message;
    $this->date = $date;
  }
}

class Guestbook {
  public $entries;
  public function __construct( $entries = array() ) {
    $this->entries = $entries;
  }
  public static function getTestInstance( $n=10 ) {
    $guestbook = new self();
    for ( $i = 0; $i < $n; ++$i ) {
      $guestbook->entries[] = new GuestbookEntry(
        sprintf( "Title %02d", $i + 1 ),
        User::getTestInstance(),
        sprintf( "Message %02d", $i + 1 ),
        time() + rand( -60 * 60 * 24, 60 * 60 * 24 ) );
    }
    return $guestbook;
  }
}