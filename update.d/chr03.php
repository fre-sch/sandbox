<?php

class chr03 extends Update
{	
	protected $requires = array( 'chr01' );
	
	protected function doUpdate()
	{
		echo __METHOD__ . "\n";
	}

}

Update::add( new chr03 );