<?php


namespace TpMinify\Factory\View\Helper;

use Interop\Container\ContainerInterface;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\View\Helper\AbstractHelper;
use TpMinify\View\Helper\HeadScript;

class HeadScriptFactory implements FactoryInterface
{
	/**
	 * Create ViewHelper
	 *
	 * @param ContainerInterface $container
	 * @return AbstractHelper
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null){
		$config = $container->get('config');
		$headScriptHelper = new HeadScript($config);
		return $headScriptHelper;
	}
}
