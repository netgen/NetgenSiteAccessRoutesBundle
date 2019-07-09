<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;

final class Matcher implements MatcherInterface
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface[]
     */
    protected $voters;

    /**
     * @param \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface[] $voters
     */
    public function __construct(array $voters)
    {
        $this->voters = $voters;
    }

    /**
     * Returns if provided siteaccess is allowed based on passed route config.
     */
    public function isAllowed(string $siteAccess, array $routeConfig): bool
    {
        foreach ($this->voters as $voter) {
            $result = $voter->vote($siteAccess, $routeConfig);
            if ($result !== VoterInterface::ABSTAIN) {
                return $result;
            }
        }

        return false;
    }
}
