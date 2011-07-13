<?php
/**
 * @package Reflex
 * @subpackage Php
 */

/**
 * Generate valid PHP code stub for classes.
 */
class Reflex_Php_Class extends ReflectionClass {
	/**
	 * Generate and echo documentation.
	 * @param string $argument
	 * @param bool $return echo documentation or return it
	 * @return null|string
	 */
	static public function export( $argument, $return = false ) {
		$self = new self( $argument );
		if ( $return )
			return $self->__toString();
		else
			echo $self->__toString();
	}
	/**
	 * Generate and echo documentation.
	 * @see ReflexClass::export
	 * @return string
	 */
	public function __toString() {
		$str = "/**\n";
		
		if ( $this->isInternal() ) {
			$str .= " * @internal\n";
		}
		
		if ( $filename = $this->getFilename() ) {
			$str .= " * @file {$filename}:{$this->getStartLine()}-{$this->getEndLine()}\n";
		}
		
		if ( $extensionName = $this->getExtensionName() ) {
			$str .= " * @extension {$extensionName}\n";
		}
		
		$str .= " */\n";
		
		if ( $this->isFinal() ) {
			$str .= "final ";
		}
		
		if ( !$this->isInterface() && $this->isAbstract() ) {
			$str .= "abstract ";
		}
		
		$classOrIface = $this->isInterface() ? "interface" : "class";
		
		$str .= "{$classOrIface} {$this->name} ";
		
		if ( $parent = $this->getParentClass() ) {
			$str .= "extends {$parent->name} ";
		}
		
		if ( count( $interfaces = $this->getInterfaceNames() ) ) {
			$str .= "implements " . implode( ", ", $interfaces ) . " ";
		}
		
		$str .= "{\n";
		
		foreach ( $this->getConstants() as $name => $value ) {
			if ( is_string( $value ) ) $value = "\"{$value}\"";
			$str .= "\tconst {$name} = {$value};\n";
		}
		
		$str .= implode( "", $this->getProperties() );
		
		$str .= implode( "", $this->getMethods() ); 
		
		return "$str}\n";
	}
	/**
	 * PHPDoc comment, false if none
	 * 
	 * @return string|bool
	 */
	public function getDocComment() {
		$doc_comment = new Reflex_DocCommentParser( parent::getDocComment() );
		return $doc_comment->parse();
	}
	/**
	 * @return Reflex_Method
	 */
	public function getConstructor() {
		$method = parent::getConstructor();
		return new Reflex_Php_Method(
			"{$this->name}::{$method->name}" );
	}
	/**
	 * @return Reflex_Method
	 */
	public function getMethod( $name ) {
		$method = parent::getMethod( $name );
		return new Reflex_Php_Method(
			"{$this->name}::{$method->name}" );
	}
	/**
	 * @see ReflectionMethod constants
	 * @param int $filter
	 * @return array Reflex_Method objects
	 */
	public function getMethods( $filter=null ) {
		if ( $filter === null )
			$filter = ReflectionMethod::IS_ABSTRACT
			        | ReflectionMethod::IS_FINAL
			        | ReflectionMethod::IS_PRIVATE
			        | ReflectionMethod::IS_PROTECTED
			        | ReflectionMethod::IS_PUBLIC
			        | ReflectionMethod::IS_STATIC;
		$methods = array();
		foreach ( parent::getMethods( $filter ) as $method )
			$methods[ $method->name ] = new Reflex_Php_Method(
				"{$this->name}::{$method->name}" );
		ksort( $methods, SORT_STRING );
		return $methods;
	}
	/**
	 * @param string $name
	 * @return Reflex_Property
	 */
	public function getProperty( $name ) {
		$property = parent::getProperty( $name );
		return new Reflex_Php_Property(
			"{$this->name}::{$property->name}" );
	}
	/**
	 * @see ReflectionProperty constants
	 * @param int $filter
	 * @return array ReflectionProperty objects
	 */
	public function getProperties( $filter=null ) {
		if ( $filter === null )
			$filter = ReflectionProperty::IS_STATIC
			        | ReflectionProperty::IS_PUBLIC
			        | ReflectionProperty::IS_PROTECTED
			        | ReflectionProperty::IS_PRIVATE;
		$properties = array();
		foreach ( parent::getProperties( $filter ) as $property )
			$properties[ $property->name ] = new Reflex_Php_Property(
				$this->name, $property->name );
		ksort( $properties, SORT_STRING );
		return $properties;
	}
	/**
	 * @return array interface name => Reflex_Class object
	 */
	public function getInterfaces() {
		$interfaces = array();
		foreach ( parent::getInterfaceNames() as $k => $interface )
			$interfaces[ $k ] = new self( $interface->name );
		return $interfaces;
	}
	/**
	 * @return Reflex_Class
	 */
	public function getParentClass() {
		$parent = parent::getParentClass();
		if ( $parent )
			return new self( $parent->name );
		return null;
	}
	/**
	 * @return array property name => property value
	 */
	public function getStaticProperties() {
		$properties = array();
		foreach ( parent::getStaticProperties() as $k => $property ) {
			$properties[$k] = new Reflex_Php_Property(
				"{$this->name}::{$property->name}" );
		}
		return $properties;
	}
	/**
	 * ReflectionExtension or null if not part of an extension
	 * @return Reflex_Extension
	 */
	public function getExtension() {
		return new Reflex_Php_Extension( $this->getExtensionName() );
	}
}
