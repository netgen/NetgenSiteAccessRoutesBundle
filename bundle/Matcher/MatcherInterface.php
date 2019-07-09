<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher;

interface MatcherInterface
{
    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     */
    public function isAllowed(string $siteAccess, array $routeConfig): bool;
}
