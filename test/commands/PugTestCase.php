<?php

/*
 * This file is part of Pug
 */

use PHPUnit\Framework\TestCase;

class PugTestCase extends TestCase
{
	/**
	 * @var	string
	 */
	static protected $binPath;

	/**
	 * @var	string
	 */
	static protected $fixturesPath;

	/**
	 * @var	string
	 */
	static protected $projectPath;

	/**
	 * @var	string
	 */
	static protected $pugfilePath;

	/**
	 * @param	string	$commandName
	 * @param	array	$arguments
	 * @return	array
	 */
	public function executePugCommand( $commandName, array $arguments=[] )
	{
		array_unshift( $arguments, $commandName );
		array_unshift( $arguments, self::$binPath );
		array_unshift( $arguments, 'PUGFILE=' . self::$pugfilePath );
		array_push( $arguments, '--no-color' );

		$command = implode( ' ', $arguments );

		$result=[];
		exec( $command, $output, $result['exit'] );

		$result['output'] = implode( PHP_EOL, $output );

		return $result;
	}

	static public function setUpBeforeClass()
	{
		self::$projectPath = dirname( dirname( dirname( __FILE__ ) ) );
		self::$binPath = self::$projectPath . '/bin/pug';
		self::$fixturesPath = self::$projectPath . '/test/fixtures';
		self::$pugfilePath = self::$fixturesPath . '/.pug';
	}

	public function tearDown()
	{
		if( file_exists( self::$pugfilePath ) )
		{
			unlink( self::$pugfilePath );
		}
	}

	/**
	 * @param	string	$filename
	 */
	public function usePugfile( $filename )
	{
		$dirPugfiles = self::$fixturesPath . '/pugfiles';

		self::$pugfilePath = self::$fixturesPath . '/.pug-' . microtime( true );

		$sourceFilename = "{$dirPugfiles}/{$filename}";
		$targetFilename = self::$pugfilePath;

		if( file_exists( $sourceFilename ) )
		{
			copy( $sourceFilename, $targetFilename );
			$this->assertEquals( file_get_contents( $sourceFilename ), file_get_contents( $targetFilename ) );
		}
		else
		{
			throw new \Exception( "Unknown pugfile '{$filename}'" );
		}
	}
}
