<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class MatcherPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('netgen_siteaccess_routes.matcher')) {
            return;
        }

        $matcher = $container->findDefinition('netgen_siteaccess_routes.matcher');
        $voters = $container->findTaggedServiceIds('netgen_siteaccess_routes.voter');

        $voterServices = array();
        foreach ($voters as $serviceName => $tag) {
            $voterServices[] = new Reference($serviceName);
        }

        $matcher->replaceArgument(0, $voterServices);
    }
}
