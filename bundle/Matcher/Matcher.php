<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Matcher;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;

final class Matcher implements MatcherInterface
{
    /**
     * @param VoterInterface[] $voters
     */
    public function __construct(
        private readonly array $voters,
    ) {}

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
