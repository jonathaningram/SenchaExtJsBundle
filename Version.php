<?php

namespace Sencha\ExtJsBundle;

final class Version
{	
	/**
	 * ExtJS version identification
	 */
	const VERSION = self::FOUR_ZERO_SEVEN;
	
	const THREE_THREE_ONE	= '3.3.1';
    const FOUR_PR_FOUR		= '4.0-pr4';
	const FOUR_PR_FIVE		= '4.0-pr5';
	const FOUR_BETA_ONE		= '4.0-beta1';
	const FOUR_BETA_TWO		= '4.0-beta2';
	const FOUR_BETA_THREE	= '4.0-beta3';
	const FOUR_ZERO_ZERO	= '4.0.0';
	const FOUR_ZERO_ONE		= '4.0.1';
	const FOUR_ZERO_TWO		= '4.0.2';
	const FOUR_ZERO_TWO_A	= '4.0.2a';
	const FOUR_ZERO_FIVE	= '4.0.5';
	const FOUR_ZERO_SIX		= '4.0.6';
	const FOUR_ZERO_SEVEN	= '4.0.7';
	
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
