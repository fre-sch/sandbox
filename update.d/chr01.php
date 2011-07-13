<?php

class chr01 extends Update
{	
	protected $requires = array();
	
	public function doUpdate()
	{
		echo __METHOD__ . "\n";
	}
}

Update::add( new chr01 );