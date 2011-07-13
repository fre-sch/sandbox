<?php
require_once dirname(__FILE__) . '/Reflex.php';

$actions = array(
	"ext" => "",
	"func" => "",
	"class" => "",
	"meth" => "",
	"prop" => "",
);

foreach ( $_SERVER[ "argv" ] as $arg ) {
	$p = strpos( $arg, ":" );
	if ( $p === false )
		continue;
	
	$action = substr( $arg, 0, $p );
	$param = substr( $arg, $p + 1 );
	
	$actions[ $action ] = $param; 
}

$r = null;

if ( !empty( $actions[ "class" ] ) ) {
	if ( !empty( $actions[ "meth" ] ) ) {
		$r = new Reflex_Php_Method(
			"{$actions["class"]}::{$actions["meth"]}" );
	}
	elseif ( !empty( $actions[ "prop" ] ) ) {
		$r = new Reflex_Php_Property(
			$actions[ "class" ], $actions[ "prop" ] );
	}
	else {
		$r = new Reflex_Php_Class(
			$actions[ "class" ] );
	}
}
elseif ( !empty( $actions[ "func" ] ) ) {
	$r = new Reflex_Php_Function( $actions[ "func" ] );
}

if ( $r ) {
	echo "{$r}\n";
}
