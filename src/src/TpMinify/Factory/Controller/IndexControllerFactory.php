<?php


namespace TpMinify\Factory\Controller;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Laminas\Session\Container;
use TpMinify\Controller\IndexController;

class IndexControllerFactory implements FactoryInterface
{
	/**
	 * Create controller
	 *
	 * @param ContainerInterface $container
	 * @return IndexController
	 */
	public function __invoke(ContainerInterface $container, $requestedName, array $options = null){

		$config = $container->get('config');
		$controller = new IndexController(
			$config
		);
		return $controller;
	}
}
