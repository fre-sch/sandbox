<?php
header( "content-type: text/plain;charset=utf-8" );

$incPath = explode( PATH_SEPARATOR, get_include_path() );
array_unshift( $incPath, realpath( dirname( __FILE__) . '/../..' ) );
set_include_path( implode( PATH_SEPARATOR, $incPath ) );

require_once 'DOMBuilder/Loader.php';

$d = new DOMBuilder();
$r = $d->root(
	$d->html(
		$d->head(
			$d->title( "Seitentitel" )
		)
		,$d->body(
			$d->div(
				$d->{"@id"}( "root" )
				,$d->div( $d->{"@id"}( "head" ) )
				,$d->div( $d->{"@id"}( "nav-site" ) )
				,$d->div( $d->{"@id"}( "nav-page" ) )
				,$d->div( $d->{"@id"}( "content" ) )
				,$d->div( $d->{"@id"}( "foot" ) )
			)
		)
	)
);
echo $d->getDocument()->saveHTML();
