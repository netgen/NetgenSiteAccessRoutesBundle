<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;

class SiteAccessVoter implements VoterInterface
{
    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     */
    public function vote(string $siteAccess, array $routeConfig): ?bool
    {
        if (in_array($siteAccess, $routeConfig, true)) {
            return true;
        }

        return VoterInterface::ABSTAIN;
    }
}
