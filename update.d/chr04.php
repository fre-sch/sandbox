<?php

class chr04 extends Update
{
	protected $requires = array( 'chr02', 'chr03' );
	
	protected function doUpdate()
	{
		echo __METHOD__ . "\n";
	}

}

Update::add( new chr04 );