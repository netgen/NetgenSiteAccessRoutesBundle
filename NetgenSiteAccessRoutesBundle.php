<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle;

use Netgen\Bundle\SiteAccessRoutesBundle\DependencyInjection\CompilerPass\MatcherPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

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
