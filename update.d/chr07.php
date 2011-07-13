<?php

class chr07 extends Update
{
	protected $requires = array( 'chr02', 'chr05', 'chr06' );
	
	protected function doUpdate()
	{
		echo __METHOD__ . "\n";
	}

}

Update::add( new chr07 );