<?php
/**
 * @package Reflex
 * @subpackage Php
 */

/**
 * Generate valid PHP code stub for properties.
 */
class Reflex_Php_Property extends ReflectionProperty {
	/**
	 * @param string $name
	 * @param string $return=false
	 * @return string
	 */
	public static function export( $name, $return=false ) {
		$self = new self( $name );
		if ( $return )
			return (string) $self;
		else
			echo (string) $self;
	}
	/**
	 * @return Reflex_Php_Class
	 */
	public function getDeclaringClass() {
		return new Reflex_Php_Class( $this->class );
	}
	/**
	 * @return Reflex_DocCommentParser
	 */
	public function getDocComment() {
		$doc_comment = new Reflex_DocCommentParser( parent::getDocComment() );
		return $doc_comment->parse();
	}
	/**
	 * @return string
	 */
	public function __toString() {
		$str .= "\t/**\n\t * @var unknown\n\t */\n\t";
		if ( $this->isPublic() ) {
			$str .= "public ";
		}
		elseif ( $this->isPrivate() ) {
			$str .= "private ";
		}
		elseif ( $this->isProtected() ) {
			$str .= "protected ";
		}
		if ( $this->isStatic() ) {
			$str .= "static ";
		}
		return "{$str}\${$this->name};\n";
	}
}
