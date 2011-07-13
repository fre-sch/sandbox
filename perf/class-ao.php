<?php
/*
 * Using or extending ArrayObject is more memory efficient for sequential usage.
 */
$basemem = memory_get_usage();
class ClassAttr extends ArrayObject {
	public function __construct() {
		$args = func_get_args();
		foreach ( $args as  $v )
			$this->offsetSet( null, $v );
	}
	public function __toString() {
		return ' class="' . implode( ' ', (array)$this ). '"';
	}
}
$c = new ClassAttr( "one", "two" );
echo $c;

echo	"\nbase:   ", $basemem,
		"\nmemory: ", $mem=memory_get_usage(),
		"\ndiff:   ", $mem - $basemem, "\n";