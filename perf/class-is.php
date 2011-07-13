<?php
/*
 * sequential array style usage of object properties uses more memory than
 * extending ArrayObject
 */
$basemem = memory_get_usage();
class ClassAttr {
	public function __construct() {
		$args = func_get_args();
		foreach ( $args as  $v )
			$this->__set( null, $v );
	}
	public function __toString() {
		return ' class="' . implode( ' ', get_object_vars( $this ) ). '"';
	}
	public function __set( $name, $value ) {
		if ( $name == null ) $name = ++$GLOBALS[__CLASS__.'::id'];
		$this->{$name} = $value;
	}
}
$c = new ClassAttr( "one", "two" );
echo $c;

echo	"\nbase:   ", $basemem,
		"\nmemory: ", $mem=memory_get_usage(),
		"\ndiff:   ", $mem - $basemem, "\n";
