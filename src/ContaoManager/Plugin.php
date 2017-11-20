<?php

namespace Ynfinite\ContaoComBundle\ContaoManager;

use Ynfinite\ContaoComBundle\YnfiniteContaoComBundle;

use Contao\CoreBundle\ContaoCoreBundle;

use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\RoutingPluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;

class Plugin implements BundlePluginInterface {
	
	public function getBundles(ParserInterface $parser) {
		return [
			BundleConfig::create(YnfiniteContaoComBundle::class)
				->setLoadAfter([ContaoCoreBundle::class])
				->setReplace(['ynfinite'])
		];
	}
}