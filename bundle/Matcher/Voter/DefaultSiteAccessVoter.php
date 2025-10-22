<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;

use function in_array;

final class DefaultSiteAccessVoter implements VoterInterface
{
    public const string DEFAULT_KEY = '_default';

    public function __construct(
        private string $defaultSiteAccess,
    ) {}

    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     */
    public function vote(string $siteAccess, array $routeConfig): ?bool
    {
        if ($siteAccess !== $this->defaultSiteAccess) {
            return VoterInterface::ABSTAIN;
        }

        if (in_array(self::DEFAULT_KEY, $routeConfig, true)) {
            return true;
        }

        return VoterInterface::ABSTAIN;
    }
}
