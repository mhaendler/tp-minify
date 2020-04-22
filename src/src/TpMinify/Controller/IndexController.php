<?php
/**
 * TpMinify - third-party module for the Zend Framework 2
 *
 * @category Module
 * @package  TpMinify
 * @author   Kanstantsin A Kamkou (2ka.by)
 * @license  http://opensource.org/licenses/MIT The MIT License (MIT)
 * @link     http://github.com/kkamkou/tp-minify/
 */

namespace TpMinify\Controller;

use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Stdlib\ResponseInterface;
use Laminas\Stdlib\RequestInterface;
use Laminas\Http\Headers;

use Minify;
use Minify_Cache_File;
use Minify_Controller_MinApp;
use Minify_Source_Factory;
use Minify_Env;


/**
 * Class Index
 * @package TpMinify\Controller
 */
class IndexController extends AbstractActionController
{

	/**
	 * @var array $config	Configuration Array from Laminas
	 */
	protected $config;

	public function __construct(Array $config){
		$this->config = $config;
	}

	/**
	 * Execute the request
	 *
	 * @param RequestInterface $request
	 * @param ResponseInterface $response (Default: null)
	 * @return \Zend\Http\PhpEnvironment\Response
	 */
	public function indexAction()
	{
		// the config hash
		$config = $this->getConfig()['TpMinify'];

		// some important stuff
		$config['serveOptions']['quiet'] = true;

		// the cache engine
		$fileCache = new Minify_Cache_File($config['cachePath'] ?: '', $config['cacheFileLocking']);
		$minify = new Minify($fileCache);

		$request = $this->getRequest();
		// check for URI versioning
		if (preg_match('~&\d~', $request->getUriString())) {
			$config['serveOptions']['maxAge'] = 31536000;
		}

		$env = new Minify_Env($config);
		$factory = new Minify_Source_Factory($env, $config);
		$controller = new Minify_Controller_MinApp($env, $factory);

		// minify result as array of information
		$result = $minify->serve($controller, $config['serveOptions']);

		// some corrections
		if (isset($result['headers']['_responseCode'])) {
			unset($result['headers']['_responseCode']);
		}

		// the headers set
		$headers = new Headers();
		$headers->addHeaders($result['headers']);

		$response = $this->getResponse();
		// final output
		return $response->setHeaders($headers)
			->setStatusCode($result['statusCode'])
			->setContent($result['content']);
	}

	protected function getConfig():array{
		return $this->config;
	}
}
