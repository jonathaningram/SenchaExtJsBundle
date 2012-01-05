<?php

namespace Sencha\ExtJsBundle;

final class Version
{	
	/**
	 * ExtJS version identification
	 */
	const VERSION = '4.1.0-beta-1';
	
	/**
	 * Constants of version parts
	 */
	
	const PART_MAJOR	= 'major';
	const PART_MINOR	= 'minor';
	const PART_MINI		= 'mini';
	const PART_SUFFIX	= 'suffix';

	/**
	 * Reverse map of version parts
	 *
	 * @var array
	 */
	private static $reverseMap = array(
		'major'		=> 1,
		'minor'		=> 2,
		'mini'		=> 3,
		'suffix'	=> 4,
	);
	
	/**
	 * Gets the version
	 *
	 * If $part is null, the entire version is returned. If it is a string,
	 * the version part is returned. If a part is given and $fromBeginning is
	 * set to true, the version is returned from the beginning to the named
	 * part.
	 *
	 * @param  string  $part
	 * @param  boolean $fromBeginning
	 * 
	 * @return string
	 */
	public static function getVersion($part = null, $fromBeginning = false)
	{
		if (is_null($part)) {
			return self::VERSION;
		}
		
		if (preg_match('(^(?P<major>\d+)\.(?P<minor>\d+)\.(?P<mini>\d+)(?P<suffix>.*)?$)', self::VERSION, $matches)) {
			if ($fromBeginning == false) {
				return $matches[$part];
			} else {
				$version = '';

				for ($i = 1; $i <= self::$reverseMap[$part]; ++$i) {
					$version .= ( ($i > 1 && $i < 4) ? '.' : '') . $matches[$i];
				}

				return $version;
			}
		}
				
		return '';
	}
}
