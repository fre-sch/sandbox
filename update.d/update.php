<?php
error_reporting(E_ALL & ~E_NOTICE);

define( "UPDATE_STATUS_FILE_PATH", dirname( __FILE__ ) . "/status.php" );
define( "UPDATE_UPDATES_DIR_PATH", dirname( __FILE__ ) );

class UpdateException extends Exception
{
}

abstract class Update
{	
	private static $updates = array();
	private static $updatesStatus = array();
	
	protected $requires = array();
	
	public static function status( $update, $status )
	{
		self::$updatesStatus[ $update ] = $status;
	}
	
	public static function add( Update $update )
	{
		$updateName = get_class( $update );
		if ( isset( self::$updates[ $updateName ] ) )
			throw new UpdateException(
				"trying to add update '{$updateName}', already exists" );
		self::$updates[ $updateName ] = $update;
	}
	
	private static function doRecursiveUpdate( $updateName )
	{
		if ( !isset( self::$updates[ $updateName ] ) )
		{
			throw new UpdateException( "missing required update {$updateName}" );
		}
		
		foreach( self::$updates[ $updateName ]->requires as $dep )
		{
			self::doRecursiveUpdate( $dep );
		}
		
		if ( !self::$updatesStatus[ $updateName ] )
		{
			self::$updates[ $updateName ]->doUpdate();
		}
		
		self::$updatesStatus[ $updateName ] = true;
	}
	
	private static function loadStatusFile()
	{
		if ( is_file( UPDATE_STATUS_FILE_PATH )
			|| file_exists( UPDATE_STATUS_FILE_PATH ) )
		{
			require_once UPDATE_STATUS_FILE_PATH;
		}
	}
	
	private static function writeStatusFile()
	{
		$statusFile = fopen( UPDATE_STATUS_FILE_PATH, "w" );
		fwrite( $statusFile, "<?php\n" );
		foreach( self::$updatesStatus as $updateName => $updateStatus )
		{
			$updateStatus = $updateStatus ? "true" : "false";
			fwrite( $statusFile,
				"Update::status( '{$updateName}', {$updateStatus} );\n" );
		}
		fclose( $statusFile );
	}
	
	private static function loadUpdates()
	{
		$updatesDir = new DirectoryIterator( UPDATE_UPDATES_DIR_PATH );
		foreach ( $updatesDir as $entry )
		{
			$entryPath = $entry->getPathname();
			if ( preg_match( '/(chr|tck)\d+.php$/', $entryPath ) )
			{
				require_once $entryPath;
			}	
		}
	}
	
	public static function run()
	{
		self::loadStatusFile();
		self::loadUpdates();
		
		foreach ( self::$updates as $updateName => $update )
		{
			self::doRecursiveUpdate( $updateName );
		}
		
		self::writeStatusFile();
	}
	
	abstract protected function doUpdate();
}

Update::run();
