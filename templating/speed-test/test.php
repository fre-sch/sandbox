<?php
define( '__DIR__', dirname( __FILE__ ) );
require_once __DIR__ . '/template_expandvars_filter.php';
require_once __DIR__ . '/MyDoc.php';

function test_include( $vars ) {
	extract( $vars );
	include __DIR__ . '/template.phtml';
}

function test_require( $vars ) {
	extract( $vars );
	require __DIR__ . '/template.phtml';
}

function test_fpassthrough( $vars ) {
	$_vars = array();
	foreach( $vars as $k => $v ) $_vars[ ":$k:" ] = $v; 
	$f = fopen( __DIR__ . '/template.html', 'r', false );
	stream_filter_append(
		$f, 'expandvars', STREAM_FILTER_READ, array( 'vars' => $_vars ) );
	fpassthru( $f );
	fclose( $f );
}

function test_file_get_contents( $vars ) {
	$names = array();
	foreach( $vars as $k => $v ) $names[] = ":$k:"; 
	echo str_replace( $names, $vars, file_get_contents( __DIR__ . '/template.html' ) );
}

function test_dom( $vars ) {
	$d = MyDoc::getInstance();
	foreach ( $vars as $k => $v ) {	
		foreach ( $d->query( "//*[@name=\"{$k}\"]" ) as $node ) {
			$node->nodeValue = $v;
		}
	}
	echo $d->saveHTML();
}

function test_readfile( $vars ) {
	$context = stream_context_create( array(
		'expandvars' => array(
			'vars' => $vars
		)
	) );
	readfile(
		'php://filter/read=expandvars/resource=file://'.__DIR__.'/template.html', false, $context );
}

$n = 100000;
$tests = array(
	'test_include',
	'test_require',
	'test_fpassthrough',
	'test_file_get_contents',
	'test_dom',
	//'test_readfile'
);
$vars = array(
	'title' => 'hello world',
	'content' => 'Lorem ipsum...',
);
$slowest_time = 0;
$slowest_func = "";
$times = array();
foreach ( $tests as $func ) {
	$total = 0;
	for ( $i = 0; $i < $n; ++$i ) {
		ob_start();
		$start = microtime( true );
		$func( $vars );
		$total += microtime( true ) - $start;
		ob_end_clean();
	}
	$times[ $func ] = $total / $n;
	if ( $slowest_time < $times[ $func ] ) {
		$slowest_time = $times[ $func ];
		$slowest_func = $func;
	} 

	echo "results for '{$func}':\n",
		"\ttotal: ", $total, "s\n",
		"\taverage: ", $times[ $func ] * 1000, "ms\n\n";
}
echo "the slowest function was $slowest_func\n";
unset( $times[ $slowest_func ] );
foreach ( $times as $func => $time ) {
	$ratio = round( 1 / ($time / $slowest_time), 2 );
	echo "\t$func was $ratio times faster\n";
}