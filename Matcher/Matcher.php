<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;

class Matcher implements MatcherInterface
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface[]
     */
    protected $voters;

    /**
     * Constructor.
     *
     * @param \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface[] $voters
     */
    public function __construct(array $voters)
    {
        $this->voters = $voters;
    }

    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     *
     * @param string $siteAccess
     * @param array $routeConfig
     *
     * @return bool
     */
    public function isAllowed($siteAccess, array $routeConfig)
    {
        foreach ($this->voters as $voter) {
            $result = $voter->vote($siteAccess, $routeConfig);
            if ($result !== VoterInterface::ABSTAIN) {
                return (bool) $result;
            }
        }

        return false;
    }
}
