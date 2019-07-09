<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;

final class DefaultSiteAccessVoter implements VoterInterface
{
    public const DEFAULT_KEY = '_default';

    /**
     * @var string
     */
    private $defaultSiteAccess;

    public function __construct(string $defaultSiteAccess)
    {
        $this->defaultSiteAccess = $defaultSiteAccess;
    }

    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     */
    public function vote(string $siteAccess, array $routeConfig): ?bool
    {
        if ($siteAccess !== $this->defaultSiteAccess) {
            return VoterInterface::ABSTAIN;
        }

        if (in_array(static::DEFAULT_KEY, $routeConfig, true)) {
            return true;
        }

        return VoterInterface::ABSTAIN;
    }
}
