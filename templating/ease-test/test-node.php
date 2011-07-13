<?php

class Attrs {
	public function __construct( $attrs=array() ) {
		foreach ( $attrs as $k => $v ) $this->{$k} = $v;
	}
	public function __toString() {
		$result = '';
		foreach ( $this as $k => $v ) {
			$result .= ' ' . $k . '="' . $v . '"';
		}
		return $result;
	}
}

function attrs( $attrs ) {
	$result = '';
	foreach( $attrs as $k => $v )
		if ( is_string( $k ) && !empty( $k ) )
			$result .= ' ' . $k . '="' . $v . '"';
	return $result;
}

function node( $name, $attrs=null, $content=null ) {
	if ( is_array( $attrs ) ) $attrs = attrs( $attrs );
	if ( $content === false ) {
		return "<{$name}{$attrs} />\n";
	}
	else {
		$args = func_get_args();
		// drop $name, $attrs
		array_shift( $args ); array_shift( $args );
		$content = implode( '', $args );
	}
	return "<{$name}{$attrs}>{$content}</{$name}>\n";
}

echo node( "html", null,
	node( "head", null,
		node( "meta", array(
			"http-equiv" => "content-type",
			"content" => "text/html;charset=UTF-8",
		), false )
		, node( "title", null, "hello world" )
	)
	, node( "body", null,
		"Lorem ipsum..."
	)
);