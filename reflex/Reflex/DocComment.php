<?php
header( 'content-type: text/plain; charset=utf-8' );
define( 'COMMENT', '
	/**
	 * The first line/sentence is a summary of the comment. Everything after
	 * the first line/sentence is a more detailed description.
	 * @author fresch
	 * @since 11.05.2008
	 * @param string $arg_three
	 * @param array $arg_two
	 * @return void
	 */
' );

class Reflex_DocComment {
	private $_rawComment = '';
	private $_cleanComment = '';

	public function __construct( $string ) {
		$this->_rawComment = $string;
		$this->_parse();
	}
	private function _parse() {
		// first strip all comment chars
		$cleanComment = preg_replace(
			'/^[ \t\/\*]*/m', '', $this->_rawComment
		);
		// split by line breaks
		$rawLines = explode( "\n", $cleanComment );
		// iterate over lines
		foreach ( $rawLines as $rawline ) {
			// skip empty lines
			if ( empty( $rawLine ) ) continue;
			
		}
	}
}

