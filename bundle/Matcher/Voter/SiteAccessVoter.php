<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;

class SiteAccessVoter implements VoterInterface
{
    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     *
     * @param string $siteAccess
     * @param array $routeConfig
     *
     * @return bool
     */
    public function vote($siteAccess, array $routeConfig)
    {
        if (in_array($siteAccess, $routeConfig, true)) {
            return true;
        }

        return VoterInterface::ABSTAIN;
    }
}
