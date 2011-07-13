<?php
/**
 * @package Reflex
 * @subpackage Php
 */

/**
 * Generate valid PHP code stub for classes.
 */
class Reflex_Html_Class extends ReflectionClass {
	/**
	 * Generate and echo documentation.
	 * @param string $argument
	 * @param bool $return echo documentation or return it
	 * @return null|string
	 */
	static public function export( $argument, $return = false ) {
		$self = new self( $argument );
		if ( $return )
			return (string) $self;
		else
			echo (string) $self;
	}
	/**
	 * Generate and echo documentation.
	 * @see Reflex_Html_Class::export
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
		
		$str = " */\n";
		
		if ( $this->isFinal() ) {
			$str .= "final ";
		}
		
		if ( $this->isAbstract() ) {
			$str .= "abstract ";
		}
		
		$str .= "class {$this->name} ";
		
		if ( $parent = $this->getParentClass() ) {
			$str .= "extends {$parent->name} ";
		}
		
		if ( count( $interfaces = $this->getInterfaceNames() ) ) {
			$str .= "implements " . implode( ", ", $interfaces ) . " ";
		}
		
		return $str;
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
		return new Reflex_Method(
			"{$this->name}::{$method->name}" );
	}
	/**
	 * @return Reflex_Method
	 */
	public function getMethod( $name ) {
		$method = parent::getMethod( $name );
		return new Reflex_Method(
			"{$this->name}::{$method->name}" );
	}
	/**
	 * @see ReflectionMethod constants
	 * @param int $filter
	 * @return array Reflex_Method objects
	 */
	public function getMethods( $filter ) {
		$methods = array();
		foreach ( parent::getMethods( $filter ) as $k => $method )
			$methods[ $k ] = new Reflex_Method(
				"{$this->name}::{$method->name}" );
		return $methods;
	}
	/**
	 * @param string $name
	 * @return Reflex_Property
	 */
	public function getProperty( $name ) {
		$property = parent::getProperty( $name );
		return new Reflex_Property(
			"{$this->name}::{$property->name}" );
	}
	/**
	 * @see ReflectionProperty constants
	 * @param int $filter
	 * @return array ReflectionProperty objects
	 */
	public function getProperties( $filter ) {
		$properties = array();
		foreach ( parent::getProperties( $filter ) as $k => $property )
			$properties[ $k ] = new Reflex_Property(
				"{$this->name}::{$property->name}" );
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
		return new self( parent::getParentClass()->name );
	}
	/**
	 * @return array property name => property value
	 */
	public function getStaticProperties() {
		$properties = array();
		foreach ( parent::getStaticProperties() as $k => $property ) {
			$properties[$k] = new Reflex_Property(
				"{$this->name}::{$property->name}" );
		}
		return $properties;
	}
	/**
	 * ReflectionExtension or null if not part of an extension
	 * @return Reflex_Extension
	 */
	public function getExtension() {
		return new Reflex_Extension( $this->getExtensionName() );
	}
	/**
	 * @return string
	 */
	public function toHtml() {
		return '<h2>' . $this->renderInternal() . 'Class ' . $this->renderExtension() . $this->renderClassName() . '</h2>' . $this->renderParents() . $this->renderInterfaces() . $this->renderConstants() . $this->renderStaticProperties();
	}
	/**
	 * @return string
	 */
	private function renderClassName() {
		return $this->name;
	}
	/**
	 * @return string
	 */
	private function renderExtension() {
		$ext = $this->getExtensionName();
		if ( $ext !== false ) {
			return '<a href="reflect.php?request=ext:' . $ext . '">' . $ext . '</a>::';
		}
	}
	/**
	 * @return string
	 */
	private function renderInternal() {
		return $this->isInternal() ? 'Internal ' : null;
	}
	/**
	 * @return string
	 */
	private function renderParents() {
		$i = 0;
		$parent = $this->getParentClass();
		if ( $parent ) {
			$parents = '<h3>Extends</h3>';
			while ( $parent ) {
				$parents .= '<ul><li><a href="reflect.php?request=class:' . $parent->getName() . '">' . $parent->getName() . '</a>';
				$parent = $parent->getParentClass();
				$i++;
			}
			return $parents . str_repeat( '</li></ul>', $i );
		}
	}
	/**
	 * @return string
	 */
	private function renderConstants() {
		$constants = $this->getConstants();
		
		if ( count( $constants ) == 0 )
			return;
		
		$html = '<h3>Constants</h3>';
		
		foreach ( $constants as $name => $value ) {
			$html .= '<p>' . $name . ' = ' . $value . '</p>';
		}
		
		return $html;
	}
	/**
	 * @return string
	 */
	private function renderStaticProperties() {
		$props = $this->getStaticProperties();
		if ( count( $props ) == 0 )
			return;
		
		$html = '<h3>Static Properties</h3>';
		
		foreach ( $props as $name => $value ) {
			$html .= '<p>' . $name . ' = ' . $value . '</p>';
		}
	}
	/**
	 * @return string
	 */
	private function renderInterfaces() {
		$interfaceNames = $this->getInterfaceNames();
		if ( count( $interfaceNames ) != 0 ) {
			$interfaces = array();
			foreach ( $interfaceNames as $interface ) {
				$interfaces[] = "<a href=\"reflect.php?request=class:$interface\">$interface</a>";
			}
			return '<h3>Interfaces</h3>' . implode( ', ', $interfaces );
		}
	}
}
