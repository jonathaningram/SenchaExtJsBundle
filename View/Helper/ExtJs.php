<?php

namespace Sencha\ExtJsBundle\View\Helper;

use \Zend_View_Helper_Abstract
use \Zend_Registry;
use \Zend_View_Interface;

use Sencha\ExtJsBundle\View\Helper\Container;
use Sencha\ExtJsBundle\View\Exception;

class ExtJs extends Zend_View_Helper_Abstract
{
	/**
	 * @var \Sencha\ExtJsBundle\View\Helper\Container
	 */
	protected $container;

	/**
	 * Initialise helper.
	 *
	 * Retrieve container from registry or create new container and store in
	 * registry.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$registry = Zend_Registry::getInstance();

		if (!isset($registry[__CLASS__])) {
			$container = new Container();
			$registry[__CLASS__] = $container;
		}

		$this->container = $registry[__CLASS__];
	}

	/**
	 * Set view object
	 *
	 * @param Zend_View_Interface $view
	 * 
	 * @return void
	 */
	public function setView(Zend_View_Interface $view)
	{
		$this->view = $view;
		$this->container->setView($view);
	}

	/**
	 * Return Ext JS container
	 *
	 * @return \Sencha\ExtJsBundle\View\Helper\Container
	 */
	public function extJs()
	{
		return $this->container;
	}

	/**
	 * Proxy to container methods
	 *
	 * @param  string $method
	 * @param  array $args
	 * 
	 * @return mixed
	 * 
	 * @throws \Sencha\ExtJsBundle\View\Exception For invalid method calls
	 */
	public function __call($method, $args)
	{
		if (!method_exists($this->container, $method)) {
			throw new \Sencha\ExtJsBundle\View\Exception(sprintf('Invalid method "%s" called on ExtJs view helper', $method));
		}

		return call_user_func_array(array($this->container, $method), $args);
	}
}
