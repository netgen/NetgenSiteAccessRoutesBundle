<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle;

use Netgen\Bundle\SiteAccessRoutesBundle\DependencyInjection\CompilerPass\MatcherPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class NetgenSiteAccessRoutesBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new MatcherPass());
    }
}
