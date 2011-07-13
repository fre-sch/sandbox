<?php
/**
 * @package Reflex
 * @subpackage Php
 */

/**
 * Generate valid PHP code stub for functions.
 */
class Reflex_Php_Function extends ReflectionFunction {
	/**
	 * @return Reflex_Php_Extension
	 */
	public function getExtension() {
		return new Reflex_Php_Extension( $this->getExtensionName() );
	}
	/**
	 * @return array
	 */
	public function getParameters() {
		$parameters = array();
		foreach ( parent::getParameters() as $k => $param ) {
			$parameters[] = new Reflex_Php_Parameter(
				$this->name, $k ); 
		}
		return $parameters;
	}
	/**
	 * @return string
	 */
	public function getDocComment() {
		$doc_comment = new Reflex_DocCommentParser( parent::getDocComment() );
		return $doc_comment->parse();
	}
	/**
	 * @return string
	 */
	public function __toString() {
		$doc = "/**\n";
		
		/*
		if ( $this->isDeprecated() ) {
			$doc .= " * @deprecated\n";
		}
		*/
		
		if ( $this->isInternal() )
			$doc .= " * @internal\n";
		
		if ( $filename = $this->getFilename() )
			$doc .= " * @file {$filename}:{$this->getStartLine()}-{$this->getEndLine()}\n";
		
		if ( $extensionName = $this->getExtensionName() )
			$doc .= " * @extension {$extensionName}\n";
		
		$parameters = array();
		foreach ( $this->getParameters() as $param ) {
			$doc .= "{$param->toDocComment()}";
			$parameters[] = "{$param}";
		}
		$parameters = implode( ", ", $parameters );
		if ( !empty( $parameters ) ) $parameters = " {$parameters} ";
		$doc .= " */\n";
		
		return "{$doc}function {$this->name}({$parameters});\n";
	}
}
