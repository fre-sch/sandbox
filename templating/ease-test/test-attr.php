<?php

class Attrs {
	public function __construct( $attrs=array() ) {
		foreach ( $attrs as $k => $v ) $this->$k = $v;
	}
	public function __toString() {
		$result = '';
		foreach ( $this as $k => $v ) {
			$result .= ' ' . $k . '="' . $v . '"';
		}
		return $result;
	}
}

function test_attrs_class( $attrs ) {
	return $attrs;
}

function test_foreach( $attrs ) {
	$result = "";
	foreach( $attrs as $k => $v ) {
		$result .= " {$k}=\"{$v}\"";
	}
	return $result;
}
function test_foreach_concat( $attrs ) {
	$result = '';
	foreach( $attrs as $k => $v ) {
		$result .= ' ' . $k . '="' . $v . '"';
	}
	return $result;
}
function test_foreach_sprintf( $attrs ) {
	$result = '';
	foreach( $attrs as $k => $v ) {
		$result .= sprintf( ' %s="%s"', $k, $v );
	}
	return $result;
}
function test_foreach_implode( $attrs ) {
	$result = array();
	foreach( $attrs as $k => $v ) {
		$result[] = " {$k}=\"{$v}\"";
	}
	return implode( '', $result );
}
function test_array_walk_helper( $v, $k, $result ) {
	$result .= " {$k}=\"{$v}\"";
}
function test_array_walk( $attrs ) {
	$result = "";
	array_walk( $attrs, 'test_array_walk_helper', &$result );
	return $result;
}

$n = 100000;
$attrs_array = array(
	'title' => 'hello world',
	'content' => 'Lorem ipsum...',
);
$attrs_object = new Attrs( array(
	'title' => 'hello world',
	'content' => 'Lorem ipsum...',
) );
$expected = " title=\"hello world\" content=\"Lorem ipsum...\"";
$slowest_time = 0;
$slowest_func = "";
$times = array();

$tests = array(
	'test_attrs_class' => 'attrs_object',
	'test_foreach' => 'attrs_array',
	'test_foreach_concat' => 'attrs_array',
	'test_foreach_sprintf' => 'attrs_array',
	'test_foreach_implode' => 'attrs_array',
	'test_array_walk' => 'attrs_array',
);
foreach ( $tests as $func => $args ) {
	$total = 0;
	$args = $$args;
	for ( $i = 0; $i < $n; ++$i ) {
		ob_start();
		$start = microtime( true );
		if ( ( $result = $func( $args ) ) !== $expected )
			die( "'{$func}' failed to produce expected result\n\t'{$expected}' !== '{$result}'\n\n" ); 
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
echo "the slowest function was '$slowest_func'\n";
unset( $times[ $slowest_func ] );
foreach ( $times as $func => $time ) {
	$ratio = round( 1 / ($time / $slowest_time), 2 );
	echo "\t'$func' was $ratio times faster\n";
}