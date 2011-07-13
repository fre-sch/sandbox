<?php

class chr02 extends Update
{
	protected $requires = array( 'chr01' );
	protected function doUpdate()
	{
		echo __METHOD__ . "\n";
	}

}

Update::add( new chr02  );