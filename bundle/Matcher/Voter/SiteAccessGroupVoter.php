<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;

final class SiteAccessGroupVoter implements VoterInterface
{
    /**
     * @var array
     */
    protected $groupsBySiteAccess;

    public function __construct(array $groupsBySiteAccess)
    {
        $this->groupsBySiteAccess = $groupsBySiteAccess;
    }

    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     */
    public function vote(string $siteAccess, array $routeConfig): ?bool
    {
        // We skip the check if siteaccess is not part of any group
        if (!isset($this->groupsBySiteAccess[$siteAccess])) {
            return VoterInterface::ABSTAIN;
        }

        // We allow the siteaccess if any group to which the siteaccess belongs
        // is in the list of allowed siteaccesses
        foreach ($this->groupsBySiteAccess[$siteAccess] as $group) {
            if (in_array($group, $routeConfig, true)) {
                return true;
            }
        }

        return VoterInterface::ABSTAIN;
    }
}
