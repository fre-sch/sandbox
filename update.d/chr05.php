<?php

class chr05 extends Update
{
	protected $requires = array( 'chr04' );
	
	protected function doUpdate()
	{
		echo __METHOD__ . "\n";
	}

}

Update::add( new chr05 );