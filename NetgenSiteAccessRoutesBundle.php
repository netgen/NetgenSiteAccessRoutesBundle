<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Netgen\Bundle\SiteAccessRoutesBundle\DependencyInjection\CompilerPass\MatcherPass;

class NetgenSiteAccessRoutesBundle extends Bundle
{
    /**
     * Builds the bundle.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MatcherPass());
    }
}
