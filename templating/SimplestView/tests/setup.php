<?php

$inc_path = explode( PATH_SEPARATOR, get_include_path() );
array_unshift( $inc_path, realpath( dirname( __FILE__ ) . "/.." ) );
set_include_path( implode( PATH_SEPARATOR, $inc_path ) );
