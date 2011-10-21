<?php

namespace Sencha\ExtJsBundle\View\Helper;

use \Zend_View_Helper_Abstract;
use \Zend_Json;

use Sencha\ExtJsBundle\Version;
use Sencha\ExtJsBundle\View\Exception as ViewException;

class Container extends Zend_View_Helper_Abstract
{
	/**
	 * Base CDN URL to utilise.
	 *
	 * @var string
	 */
	protected $_cdnBase = null;

	/**
	 * Path segment following version string of CDN path.
	 * 
	 * @var string
	 */
	protected $_cdnExtPath = null;
	
	/**
	 * Ext version to use from CDN.
	 * 
	 * @var string
	 */
	protected $_cdnVersion = null;

	/**
	 * Base local URL to utilise.
	 *
	 * @var string
	 */
	protected $_localBase = null;

	/**
	 * Path segment following version string of local path.
	 *
	 * @var string
	 */
	protected $_localExtPath = null;

	/**
	 * Ext version to use from local.
	 *
	 * @var string
	 */
	protected $_localVersion = null;

	/**
	 * Ext configuration.
	 *
	 * @var array
	 */
	protected $_extConfig = array();
	
	/**
     * A flag to indicate if Ext JS is enabled.
	 * 
     * @var bool
     */
    protected $_enabled = false;
	
	/**
	 * A flag to indicate if the loader is disabled.
	 * 
	 * @var bool
	 */
	protected $_disableLoader = false;
	
	/**
	 * A flag to indicate if caching is disabled.
	 * 
	 * @var bool
	 */
	protected $_disableCaching = false;
	
	/**
	 * Determines if we rendering as XHTML.
	 * 
	 * @var bool
	 */
	protected $_isXhtml = false;

	/**
	 * Arbitrary JavaScript to include in Ext script.
	 * 
	 * @var array
	 */
	protected $_javaScriptStatements = array();

	/**
     * Modules to require.
	 * 
     * @var array
     */
    protected $_modules = array();

	/**
	 * Registered module paths.
	 * 
	 * @var array
	 */
	protected $_modulePaths = array();

	/**
	 * Actions to perform on window load.
	 * 
	 * @var array
	 */
	protected $_onLoadActions = array();

	/**
	 * Style sheet modules to load.
	 *
	 * @var array
	 */
	protected $_stylesheetModules = array();

	/**
	 * Local stylesheets.
	 *
	 * @var array
	 */
	protected $_stylesheets = array();

	/**
     * Register the Ext stylesheet?
	 * 
     * @var bool
     */
    protected $_registerExtStylesheet = false;
	
	/**
     * Enables Ext JS.
     *
     * @return Container
     */
    public function enable()
    {
        $this->_enabled = true;

		return $this;
    }

    /**
     * Determines if Ext JS is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->_enabled;
    }
	
	public function getEnabledVersions()
	{
		return array_keys($this->_enabledVersions);
	}
	
	/**
     * Determines if the Ext JS loader is enabled.
     *
     * @return bool
     */
    public function isLoaderEnabled()
    {
        return $this->_disableLoader == false;
    }
	
	/**
     * Determines if the Ext JS loader is disabled.
     *
     * @return bool
     */
    public function isLoaderDisabled()
    {
        return $this->_disableLoader == true;
    }
	
	public function enableLoader()
	{
		$this->_disableLoader = false;
		return $this;
	}
	
	public function disableLoader()
	{
		$this->_disableLoader = true;
		return $this;
	}
	
	public function enableCaching()
	{
		$this->_disableCaching = false;
		return $this;
	}
	
	public function disableCaching()
	{
		$this->_disableCaching = true;
		return $this;
	}

	/**
	 * Specify one or more modules to require.
	 *
	 * @param  string|array $modules The modules to require.
	 * @return Container
	 */
	public function requireModule($modules)
	{
		if (!is_string($modules) && !is_array($modules)) {
			throw new ViewException('Invalid module name specified; must be a string or an array of strings');
		}

		$modules = (array) $modules;

		foreach ($modules as $module) {
			if (!in_array($module, $this->_modules)) {
				$this->_modules[] = $module;
			}
		}

		return $this;
	}

	/**
	 * Retrieve list of modules to require.
	 *
	 * @return array
	 */
	public function getModules()
	{
		return $this->_modules;
	}

	/**
	 * Register a module path.
	 *
	 * @param  string $module The module for which to register a path.
	 * @param  string $path The path to register for the module.
	 * @return Container
	 */
	public function registerModulePath($module, $path)
	{
		$path = (string) $path;

		if (!in_array($module, $this->_modulePaths)) {
			$this->_modulePaths[$module] = $path;
		}

		return $this;
	}

	/**
	 * List registered module paths.
	 *
	 * @return array
	 */
	public function getModulePaths()
	{
		return $this->_modulePaths;
	}

	/**
	 * Set CDN base path.
	 *
	 * @param  string $url
	 * @return Container
	 */
	public function setCdnBase($url)
	{
		$this->_cdnBase = (string) $url;
		return $this;
	}

	/**
	 * Return CDN base URL.
	 *
	 * @return string
	 */
	public function getCdnBase()
	{
		return $this->_cdnBase;
	}

	/**
	 * Use CDN, using the specified version.
	 *
	 * @param  string $version
	 * @return Container
	 */
	public function setCdnVersion($version = null)
	{
		$this->enable();

		if (preg_match('/^[1-9]\.[0-9](\.[0-9])?$/', $version)) {
			$this->_cdnVersion = $version;
		}
		
		return $this;
	}

	/**
	 * Gets the CDN version.
	 *
	 * @return string
	 */
	public function getCdnVersion()
	{
		return $this->_cdnVersion;
	}

	/**
	 * Sets the CDN path to Ext (relative to CDN base + version).
	 *
	 * @param  string $path
	 * @return Container
	 */
	public function setCdnExtPath($path)
	{
		$this->_cdnExtPath = (string) $path;
		return $this;
	}

	/**
	 * Get CDN path to Ext (relative to CDN base + version).
	 *
	 * @return string
	 */
	public function getCdnExtPath()
	{
		return $this->_cdnExtPath;
	}

	/**
	 * Determines if we using the CDN.
	 *
	 * @return bool
	 */
	public function useCdn()
	{
		return !$this->useLocal();
	}

	/**
	 * Set local base path.
	 *
	 * @param  string $url
	 * @return Container
	 */
	public function setLocalBase($url)
	{
		$this->_localBase = (string) $url;
		return $this;
	}

	/**
	 * Return local base URL.
	 *
	 * @return string
	 */
	public function getLocalBase()
	{
		return $this->_localBase;
	}

	/**
	 * Use local, using the specified version.
	 *
	 * @param  string $version
	 * @return Container
	 */
	public function setLocalVersion($version = null)
	{
		$this->enable();

		$this->_localVersion = $version;

		return $this;
	}

	/**
	 * Gets the local version.
	 *
	 * @return string
	 */
	public function getLocalVersion()
	{
		return $this->_localVersion;
	}

	/**
	 * Sets the local path to Ext (relative to local base + version).
	 *
	 * @param  string $path
	 * @return Container
	 */
	public function setLocalExtPath($path)
	{
		$this->_localExtPath = (string) $path;
		
		return $this;
	}

	/**
	 * Get local path to Ext (relative to local base + version).
	 *
	 * @return string
	 */
	public function getLocalExtPath()
	{
		return $this->_localExtPath;
	}

	/**
     * Determines if we are using a local path.
     *
     * @return bool
     */
	public function useLocal()
	{
		return (null === $this->_localBase || null === $this->_localVersion || null === $this->_localExtPath) ? false : true;
	}

	/**
	 * Retrieve Ext configuration values.
	 *
	 * @return array
	 */
	public function getExtConfig()
	{
		return $this->_extConfig;
	}

	/**
	 * Add a stylesheet by module name.
	 *
	 * @param  string $module
	 * @return Container
	 */
	public function addStylesheetModule($module)
	{
		if (!preg_match('/^[a-z0-9]+\.[a-z0-9_-]+(\.[a-z0-9_-]+)*$/i', $module)) {
			throw new ViewException('Invalid stylesheet module specified');
		}

		if (!in_array($module, $this->_stylesheetModules)) {
			$this->_stylesheetModules[] = $module;
		}
		
		return $this;
	}

	/**
	 * Get all stylesheet modules currently registered
	 *
	 * @return array
	 */
	public function getStylesheetModules()
	{
		return $this->_stylesheetModules;
	}

	/**
	 * Add a stylesheet
	 *
	 * @param  string $path
	 * @return Container
	 */
	public function addStylesheet($path)
	{
		$path = (string) $path;
		
		if (!in_array($path, $this->_stylesheets)) {
			$this->_stylesheets[] = (string) $path;
		}

		return $this;
	}

	/**
	 * Register the ext-all.css stylesheet?
	 *
	 * With no arguments, returns the status of the flag; with arguments, sets
	 * the flag and returns the object.
	 *
	 * @param  null|bool $flag
	 * @return Container|bool
	 */
	public function registerExtStylesheet($flag = null)
	{
		if (null === $flag) {
			return $this->_registerExtStylesheet;
		}

		$this->_registerExtStylesheet = (bool) $flag;
		
		return $this;
	}

	/**
	 * Retrieve registered stylesheets.
	 *
	 * @return array
	 */
	public function getStylesheets()
	{
		return $this->_stylesheets;
	}

	/**
	 * Add a script to execute onLoad.
	 *
	 * Ext.onReady accepts:
	 * - function name
	 * - lambda
	 *
	 * @param  string $callback Lambda
	 * @return Container
	 */
	public function addOnLoad($callback)
	{
		if (!in_array($callback, $this->_onLoadActions, true)) {
			$this->_onLoadActions[] = $callback;
		}
		
		return $this;
	}

	/**
	 * Prepend an onLoad event to the list of onLoad actions.
	 *
	 * @param  string $callback Lambda
	 * @return Container
	 */
	public function prependOnLoad($callback)
	{
		if (!in_array($callback, $this->_onLoadActions, true)) {
			array_unshift($this->_onLoadActions, $callback);
		}

		return $this;
	}

	/**
	 * Retrieve all registered onLoad actions.
	 *
	 * @return array
	 */
	public function getOnLoadActions()
	{
		return $this->_onLoadActions;
	}

	/**
	 * Return all registered JavaScript statements.
	 *
	 * @return array
	 */
	public function getJavaScript()
	{
		return $this->_javaScriptStatements;
	}

	/**
	 * String representation of Ext environment.
	 *
	 * @return string
	 */
	public function __toString()
	{
		if (!$this->isEnabled()) {
			return '';
		}

		$this->_isXhtml = $this->view->doctype()->isXhtml();

		$xhtml = '';
		
		$xhtml .= $this->renderStylesheets() . PHP_EOL
				. $this->renderExtConfig() . PHP_EOL
				. $this->renderExtScriptTag() . PHP_EOL
				. $this->renderExtras();
		
		return $xhtml;
	}

	/**
	 * Render Ext stylesheets.
	 *
	 * @return string
	 */
	protected function renderStylesheets()
	{
		if ($this->useCdn()) {
			$base = $this->getCdnBase()
					. $this->getCdnVersion();
		} else {
			$base = $this->getLocalBase()
					. $this->getLocalVersion();
		}

		$registeredStylesheets = $this->getStylesheetModules();
		
		foreach ($registeredStylesheets as $stylesheet) {
			$themeName = substr($stylesheet, strrpos($stylesheet, '.') + 1);

			$stylesheet = str_replace('.', '/', $stylesheet);
			
			$stylesheets[] = $base . '/' . $stylesheet . '/' . $themeName . '.css';
		}

		foreach ($this->getStylesheets() as $stylesheet) {
			$stylesheets[] = $stylesheet;
		}

		if ($this->_registerExtStylesheet) {
			$stylesheets[] = $base . '/resources/css/ext-all-gray.css';
		}

		if (empty($stylesheets)) {
			return '';
		}

		array_reverse($stylesheets);

		foreach ($stylesheets as $stylesheet) {
			$this->view->headLink()->prependStylesheet($stylesheet);
		}

		return '';
	}

	/**
	 * Render extConfig values.
	 *
	 * @return string
	 */
	protected function renderExtConfig()
	{
		$extConfigValues = $this->getExtConfig();
		
		if (empty($extConfigValues)) {
			return '';
		}

		$scriptTag = '<script type="text/javascript">' . PHP_EOL
				. (($this->_isXhtml) ? '//<![CDATA[' : '//<!--') . PHP_EOL
				. '    var extConfig = ' . Zend_Json::encode($extConfigValues) . ';' . PHP_EOL
				. (($this->_isXhtml) ? '//]]>' : '//-->') . PHP_EOL
				. '</script>';

		return $scriptTag;
	}

	/**
	 * Render Ext script tag.
	 *
	 * Renders Ext script tag by utilising either local path provided or the
	 * CDN. If any extConfig values were set, they will be serialised and passed
	 * with that attribute.
	 *
	 * @return string
	 */
	protected function renderExtScriptTag()
	{
		if ($this->useCdn()) {
			$source = $this->getCdnBase()
					. $this->getCdnVersion()
					. $this->getCdnExtPath();
		} else {
			$source = $this->getLocalBase()
					. $this->getLocalVersion()
					. $this->getLocalExtPath();
		}

		$this->view->headScript()->prependFile($source, 'text/javascript');
		
		return '';
	}

	/**
	 * Render module paths and requires.
	 *
	 * @return string
	 */
	protected function renderExtras()
	{
		$js = array();

		$modulePaths = $this->getModulePaths();
		
		if (!empty($modulePaths)) {
			$js[] = 'Ext.Loader.setConfig({enabled: ' . ($this->_disableLoader ? 'false' : 'true') . ', disableCaching: ' . ($this->_disableCaching ? 'true' : 'false') . '});' . "\n";
			
			if ($this->_disableLoader == false) {
				foreach ($modulePaths as $module => $path) {
					$js[] = 'Ext.Loader.setPath("' . $this->view->escape($module) . '", "' . $this->view->escape($path) . '");';
				}
			}
		}

		if ($this->_disableLoader == false) {
			$modules = $this->getModules();

			if (!empty($modules)) {
				$js[] = 'Ext.require([';

				foreach ($modules as $i => $module) {
					$js[] = "\t" . '\'' . $this->view->escape($module) . '\'' . ($i != count($modules) - 1 ? ',' : '');
				}

				$js[] = ']);' . "\n";
			}
		}

		$onLoadActions = array();
		
		/**
		 * Get all other onLoad actions.
		 */
		foreach ($this->getOnLoadActions() as $callback) {
			$onLoadActions[] = 'Ext.onReady(' . $callback . ');';
		}

		$javaScript = implode("\n    ", $this->getJavaScript());

		$content = '';
		
		if (!empty($js)) {
			$content .= implode("\n", $js) . "\n";
		}

		if (!empty($onLoadActions)) {
			$content .= implode("\n    ", $onLoadActions) . "\n";
		}

		if (!empty($javaScript)) {
			$content .= $javaScript . "\n";
		}

		if (preg_match('/^\s*$/s', $content)) {
			return '';
		}

		$xhtml = '<script type="text/javascript">' . PHP_EOL
				. (($this->_isXhtml) ? '//<![CDATA[' : '//<!--') . PHP_EOL
				. $content
				. (($this->_isXhtml) ? '//]]>' : '//-->') . PHP_EOL
				. PHP_EOL . '</script>';
		
		return $xhtml;
	}
}
