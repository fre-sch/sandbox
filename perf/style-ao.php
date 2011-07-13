<?php
/*
 * Using or extending ArrayObject for random access associative usage is less
 * memory efficient than using "dynamic" object properties.
 */
class StyleAttr extends ArrayObject {
	public function __toString() {
		$str = ' style="';
		foreach ( $this as $p => $v ) {
			$str .= "{$p}:{$v};";
		}
		return "{$str}\"";
	}
}
$s = new StyleAttr();
$s["background-color"] = "#ff0000";
$s["font-size"] = "12pt";
echo $s;
