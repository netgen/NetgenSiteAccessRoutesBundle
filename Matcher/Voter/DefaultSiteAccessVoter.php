<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;

class DefaultSiteAccessVoter implements VoterInterface
{
    const DEFAULT_KEY = '_default';

    /**
     * @var string
     */
    protected $defaultSiteAccess;

    /**
     * Constructor.
     *
     * @param string $defaultSiteAccess
     */
    public function __construct($defaultSiteAccess)
    {
        $this->defaultSiteAccess = $defaultSiteAccess;
    }

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
        if ($siteAccess !== $this->defaultSiteAccess) {
            return VoterInterface::ABSTAIN;
        }

        if (in_array(static::DEFAULT_KEY, $routeConfig)) {
            return true;
        }

        return VoterInterface::ABSTAIN;
    }
}
