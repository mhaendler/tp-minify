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

		// the time correction
		Minify::$uploaderHoursBehind = $config['uploaderHoursBehind'];

		// the cache engine
		Minify::setCache($config['cachePath'] ?: '', $config['cacheFileLocking']);

		// doc root corrections
		if ($config['documentRoot']) {
			$_SERVER['DOCUMENT_ROOT'] = $config['documentRoot'];
			Minify::$isDocRootSet = true;
		}

		$request = $this->getRequest();
		// check for URI versioning
		if (preg_match('~&\d~', $request->getUriString())) {
			$config['serveOptions']['maxAge'] = 31536000;
		}

		// minify result as array of information
		$result = Minify::serve('MinApp', $config['serveOptions']);

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
