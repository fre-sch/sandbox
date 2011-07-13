<?php
require_once dirname( __FILE__ ) . '/Reflex.php';

$opts = explode( " ", $_GET[ "request" ] );
$action = array_shift( $opts );
$reflector = null;
try {
	switch ( $action ) {
		case 'e':
			$reflector = new ReflectionExtension( $opts[ 0 ] );
			break;
		case 'c':
			$reflector = new Reflex_Php_Class( $opts[ 0 ] );
			break;
		case 'm':
			$reflector = new ReflectionMethod( $opts[ 0 ], $opts[ 1 ] );
			break;
		case 'f':
			$reflector = new ReflectionFunction( $opts[ 0 ] );
			break;
	}
}
catch (ReflectionException $e) {
	$error = $e;
}


?><!DOCTYPE html>
<html>
	<head>
		<title>Reflecting: <?php echo $reflector !== null ? $reflector->getName() : get_class( $error ) ?></title>
		<link rel="stylesheet" type="text/css" href="reflect.css" media="screen"/>
	</head>
	<body>

<div id="root">

	<div id="head">

		<div class="bottom-aligned">
			<h1>Reflecting: <?php echo $reflector !== null ? $reflector->getName() : get_class( $error ) ?></h1>

			<div id="nav">
				<a href="?request=e standard">Standard</a>
				<a href="?request=e SPL">SPL</a>
				<a href="?request=e Reflection">Reflection</a>
			</div>

		</div>

	</div>

	<div id="content">
		<div class="pad">
			<pre><?php echo $reflector !== null ? $reflector : $error ?></pre>
		</div>
	</div>

	<div id="foot">
		<form id="command" method="get" action="index.php">
			Request:
			<input type="text" name="request" size="60" value="<?php echo $_GET[ 'request' ] ?>"/>
			<button type="submit">Reflect</button>
		</form>
	</div>

</div>

	</body>
</html>

