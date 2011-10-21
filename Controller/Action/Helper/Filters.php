<?php

namespace Sencha\ExtJsBundle\Controller\Action\Helper;

use Sencha\ExtJsBundle\Controller\Plugin\ParamsHandler;

use \Zend_Controller_Action_Helper_Abstract;

class Filters extends Zend_Controller_Action_Helper_Abstract
{
	/**
	 * Filter parameters detected in the request
	 * 
	 * @var array 
	 */
	protected $_filterParams = array();

	/**
	 * Reformat the filters
	 *
	 * @return void
	 */
	public function init()
	{
		$request = $this->getRequest();

		$filter = $request->getParam(ParamsHandler::FILTER, false);

		if (!$filter) {
			return;
		}

		$filter = array_values($filter);

		$filterParams = array();

		foreach ($filter as $f) {
			if (isset($f[ParamsHandler::FILTER_PROPERTY])) {
				$filterProperty = $f[ParamsHandler::FILTER_PROPERTY];
			} else {
				throw new \Exception('Found a filter, but the property key was not provided.');
			}
			
			if (isset($f[ParamsHandler::FILTER_VALUE])) {
				$filterValue = $f[ParamsHandler::FILTER_VALUE];
			} else {
				throw new \Exception('The value key for the property ' . $filterProperty . ' was not provided.');
			}

			$filterParams[$filterProperty] = $filterValue;
		}

		$this->setFilterParams($filterParams);
	}

	/**
	 * Set body params
	 *
	 * @param  array $params
	 * 
	 * @return Sencha\ExtJsBundle\Controller\Action\Helper\Filters
	 */
	public function setFilterParams(array $params)
	{
		$this->_filterParams = $params;
		return $this;
	}

	/**
	 * Retrieves the filter parameters
	 *
	 * @return array
	 */
	public function getFilterParams()
	{
		return $this->_filterParams;
	}

	/**
	 * Determines if the filter parameter is set
	 *
	 * @return bool
	 */
	public function hasFilterParams()
	{
		if (!empty($this->_filterParams)) {
			return true;
		}
		
		return false;
	}

	/**
	 * Gets the filters, if such filters exist
	 *
	 * @return array
	 */
	public function getFilters()
	{
		if ($this->hasFilterParams()) {
			return $this->getFilterParams();
		}

		return array();
	}

	public function direct()
	{
		return $this->getFilters();
	}
}
