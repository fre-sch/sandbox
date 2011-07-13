<?php
/**
 * @package Reflex
 * @subpackage Php
 */

/**
 * Generate and export documentation for function and method parameters
 */
class Reflex_Php_Parameter extends ReflectionParameter {
	/**
	 * Generate a PHPDoc comment for this parameter.
	 *
	 * @return string
	 */
	public function toDocComment() {
		$byReference = null;
		if ( $this->isPassedByReference() ) {
			$byReference = "&";
		}
		
		$defaultValue = null;
		if ( $this->isDefaultValueAvailable() ) {
			$defaultValue = "={$this->getDefaultValue()}";
		}
		if ( empty( $defaultValue ) && $this->isOptional() || $this->allowsNull() ) {
			$defaultValue = "=null";
		}
		
		$type = null;
		if ( $this->isArray() ) {
			$type = "array";
		}
		elseif ( $class = $this->getClass() ) {
			$type = $class->name;
		}
		elseif ( $this->isDefaultValueAvailable() ) {
			$type = gettype( $defaultValue );
		}
		else {
			$type = "mixed";
		}
		
		return " * @param {$type} {$byReference}{$this->getName()}{$defaultValue}\n";
	}
	/**
	 * Generate valid PHP code for this parameter.
	 * 
	 * @return string
	 */
	public function __toString() {
		$byReference = null;
		if ( $this->isPassedByReference() ) {
			$byReference = "&";
		}
		
		$defaultValue = null;
		if ( $this->isDefaultValueAvailable() ) {
			$defaultValue = "={$this->getDefaultValue()}";
		}
		if ( !$defaultValue && $this->isOptional() || $this->allowsNull() ) {
			$defaultValue = "=null";
		}
		
		return "{$byReference}{$this->getName()}{$defaultValue}";
	}
}
