<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;

interface VoterInterface
{
    const ABSTAIN = 'abstain';

    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     *
     * @param string $siteAccess
     * @param array $routeConfig
     *
     * @return bool
     */
    public function vote($siteAccess, array $routeConfig);
}
