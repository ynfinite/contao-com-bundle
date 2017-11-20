<?php

namespace Ynfinite\ContaoComBundle\ContaoManager;

use Ynfinite\ContaoComBundle\YnfiniteContaoComBundle;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\RoutingPluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface {
	
	public function getBundles(ParserInterface $parser) {
		return [
			BundleConfig::create(YnfiniteComBundle::class)
				->setLoadAfter([
					"Contao\CoreBundle\ContaoCoreBundle",
					"Contao\ManagerBundle\ContaoManagerBundle"
				])
				->setReplace(['ynfinite'])
		];
	}

	public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel) {
		return $resolver->resolve(__DIR__.'/../Resources/config/routing.yml')
			->load(__DIR__.'/../Resources/config/routing.yml');
	}

}