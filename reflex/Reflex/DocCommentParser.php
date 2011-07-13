<?php

class Reflex_DocCommentParser {
	const PARAM_TOK		= '@param';
	const RETURN_TOK	= '@return';
	const SINCE_TOK		= '@since';
	const AUTHOR_TOK	= '@author';
	const VAR_TOK		= '@var';
	const SEE_TOK		= '@see';
	const LINK_TOK		= '@link';

	protected $docCommentString = '';
	protected $authorDivider = ',';
	protected $isParsed = false;

	public $summary = '';
	public $description = '';

	public $params = array(
		'types' => array(),
		'descriptions' => array(),
	);
	public $return = array(
		'type' => array(),
		'description' => array(),
	);
	public $since = '';
	public $authors = array();

	public function __construct($doc_comment) {
		$this->docCommentString = $doc_comment;
	}
	public function __toString() {
		return $this->docCommentString;
	}
	public function parse() {
		$lines = explode("\n", $this->docCommentString);
		foreach ($lines as $line) {
			$line = preg_replace('|^\s*[/*]+\s*|', '', $line);
			$line = trim($line);

			if (empty($line)) {
				continue;
			}

			if ($line{0} == '@') {
				$matches = array();
				preg_match('|(@\w+)\s+(\w+)\s*(.*)|', $line, $matches);
				var_dump($matches);
				switch ($matches[1]) {
					case PARAM_TOK:
						$this->params['types'][] = $matches[2];
						$this->params['descriptions'][] = $matches[3];
						break;
					case RETURN_TOK:
						$this->return['types'][] = $matches[2];
						$this->return['descriptions'][] = $matches[3];
						break;
					case SINCE_TOK:
						$this->since = $matches[2];
						break;
					case AUTHOR_TOK:
						$this->authors = array_map('trim', explode($this->authorDivider, $line));
						break;
					case VAR_TOK:
						break;
					case SEE_TOK:
						break;
					case LINK_TOK:
						break;
				}
			}
			else {
				if (empty($this->summary)) {
					$this->summary = $line;
				}
				else {
					$this->description .= $line . '\n';
				}
			}
		}
		$this->isParsed = true;
		return $this;
	}
}