<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;

interface VoterInterface
{
    public const ABSTAIN = null;

    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     *
     * @param string $siteAccess
     * @param array $routeConfig
     *
     * @return bool|null
     */
    public function vote(string $siteAccess, array $routeConfig): ?bool;
}
