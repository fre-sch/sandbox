<?php
/**
 * Attempt to create HTML-building interface, similar to jQuery syntax or Zend_Database_Statement
 */
class DOMBuilder {
	/**
	 * @var DOMDocument
	 */
	private $document;
	/**
	 * @var DOMBuilder_Element
	 */
	private $root;
	/**
	 * @param DOMDocument $document
	 */
	public function __construct( DOMDocument $document=null ) {
		if ( $document )
			$this->document = $document;
		else
			$this->document = new DOMDocument();
		$this->document->formatOutput = true;
	}
	/**
	 * @param string $name
	 * @param array $arguments
	 */
	public function __call( $name, $arguments ) {
		if ( $name[0] == '@' ) {
			$attribute = $this->document->createAttribute( substr( $name, 1 ) );
			$attribute->value = $arguments[ 0 ];
			return $attribute;
		}
		else {
			$node = $this->document->createElement( $name );
			foreach ( $arguments as $arg ) {
				if ( !$arg instanceof DOMNode )
					$arg = $this->document->createTextNode( (string) $arg );
				$node->appendChild( $arg );
			}
			return $node;
		}
	}
	/**
	 * @return DOMDocument
	 */
	public function getDocument() {
		return $this->document;
	}
	/**
	 * Set and get root element.
	 *
	 * @param string $name
	 * @return DOMBuilder_Element
	 */
	public function root( $root=false ) {
		if ( $root !== false ) {
			if ( $this->root ) {
				$this->document->removeChild( $this->root );	
			}
			if ( $root instanceof DOMElement ) {
				$this->root = $root;
				$this->document->appendChild( $this->root );
			}
			else {
				$this->root = $this->document->createElement( $root );
			}
		}
		return $this->root;
	}
}
