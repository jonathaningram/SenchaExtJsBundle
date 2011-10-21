<?php

namespace Sencha\ExtJsBundle\Controller\Plugin;

use \Zend_Controller_Plugin_Abstract;
use \Zend_Controller_Request_Abstract;
use \Zend_Controller_Request_Http;
use \Zend_Json;

class ParamsHandler extends Zend_Controller_Plugin_Abstract
{
	const DC				= '_dc';
	const FILTER			= 'filter';
	const FILTER_PROPERTY	= 'property';
	const FILTER_VALUE		= 'value';
	
	public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request)
	{
		if (!$request instanceof Zend_Controller_Request_Http) {
			return;
		}

		$dc = $request->getParam(self::DC, false);

		/**
		 * Completely remove the DC parameter from the GET variable (i.e.
		 * request).
		 */
		if ($dc != false) {
			unset($_GET[self::DC]);
		}

		$filter = $request->getParam(self::FILTER, false);

		/**
		 * Decode the filter.
		 */
		if ($filter != false) {
			$request->setParam(self::FILTER, Zend_Json::decode($filter));
		}
	}
}
