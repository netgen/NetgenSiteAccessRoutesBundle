<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher\Voter;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\DefaultSiteAccessVoter;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use PHPUnit\Framework\TestCase;

final class DefaultSiteAccessVoterTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\DefaultSiteAccessVoter
     */
    private $voter;

    protected function setUp(): void
    {
        $this->voter = new DefaultSiteAccessVoter('eng');
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\DefaultSiteAccessVoter::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\DefaultSiteAccessVoter::vote
     *
     * @dataProvider voteProvider
     */
    public function testVote(string $siteAccess, array $groupConfig, bool $vote): void
    {
        self::assertSame($vote, $this->voter->vote($siteAccess, $groupConfig));
    }

    public function voteProvider(): array
    {
        return [
            ['cro', ['_default'], VoterInterface::ABSTAIN],
            ['eng', ['_default'], true],
            ['extra', ['_default'], VoterInterface::ABSTAIN],
            ['cro', ['cro'], VoterInterface::ABSTAIN],
            ['eng', ['cro'], VoterInterface::ABSTAIN],
            ['extra', ['cro'], VoterInterface::ABSTAIN],
        ];
    }
}
