<?php
/**
 * @package Reflex
 * @subpackage Php
 */

/**
 * Generate valid PHP code stub for methods.
 */
class Reflex_Php_Method extends ReflectionMethod {
	/**
	 * @return Reflex_Php_Class
	 */
	public function getDeclaringClass() {
		$class = parent::getDeclaringClass();
		$declaringClass = $class;
		while ( $class && $class->hasMethod( $this->name ) ) {
			$declaringClass = $class;
			$class = $class->getParentClass();
		}
		if ( count( $ifaces = $declaringClass->getInterfaces() ) ) {
			foreach ( $ifaces as $iface ) {
				if ( $iface->hasMethod( $this->name ) )
					return new Reflex_Php_Class( $iface->name );
			}
		}
		return new Reflex_Php_Class( $declaringClass->name );
	}
	/**
	 * @return string
	 */
	public function getDocComment() {
		$doc_comment = new Reflex_DocCommentParser( parent::getDocComment() );
		return $doc_comment->parse();
	}
	/**
	 * @return array
	 */
	public function getParameters() {
		$parameters = array();
		foreach ( parent::getParameters() as $k => $param) {
			$parameters[ $k ] = new Reflex_Php_Parameter(
				array( $this->class, $this->name ), $k );
		}
		return $parameters;
	}
	/**
	 * @param string $class
	 * @param string $name
	 * @param string $return=false
	 * @return string
	 */
	public static function export( $class, $name, $return = false ) {
		$self = new self( $class, $name );
		if ( $return )
			return "{$self}";
		else
			echo "{$self}";
	}
	/**
	 * @return string
	 */
	public function __toString() {
		$doc = "\t/**\n";
		
		if ( $this->isDeprecated() ) {
			$doc .= "\t * @deprecated\n";
		}
		
		if ( $this->isInternal() ) {
			$doc .= "\t * @internal\n";
		}
		
		$declaringClass = $this->getDeclaringClass()->name;
		if ( $this->class != $declaringClass )
			$doc .= "\t * @declaring-class {$this->getDeclaringClass()->name}\n";
		
		if ( $filename = $this->getFilename() ) {
			$doc .= "\t * @file {$filename}:{$this->getStartLine()}-{$this->getEndLine()}\n";
		}
		/*
		if ( $extensionName = $this->getExtensionName() ) {
			$doc .= "\t * @extension {$extensionName}\n";
		}
		*/
		
		$parameters = array();
		foreach ( $this->getParameters() as $param ) {
			$doc .= "\t{$param->toDocComment()}";
			$parameters[] = "\${$param}";
		}
		$parameters = implode( ", ", $parameters );
		if ( !empty( $parameters ) ) $parameters = " {$parameters} ";
		$doc .= "\t */\n";
		
		$name = "\t";
		if ( $this->isAbstract() ) {
			$name .= "abstract ";
		}
		
		if ( $this->isPublic() ) {
			$name .= "public "; 
		}
		elseif ( $this->isPrivate() ) {
			$name .= "private ";
		}
		elseif ( $this->isProtected() ) {
			$name .= "protected ";
		}
		
		if ( $this->isStatic() ) {
			$name .= "static ";
		}
		$name .= "function {$this->name}";
		
		return "{$doc}{$name}({$parameters});\n";
	}
}
