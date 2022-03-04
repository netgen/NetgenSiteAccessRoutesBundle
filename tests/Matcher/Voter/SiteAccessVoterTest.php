<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher\Voter;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessVoter;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use PHPUnit\Framework\TestCase;

final class SiteAccessVoterTest extends TestCase
{
    private SiteAccessVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new SiteAccessVoter();
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessVoter::vote
     *
     * @dataProvider voteProvider
     */
    public function testVote(string $siteAccess, array $groupConfig, ?bool $vote): void
    {
        self::assertSame($vote, $this->voter->vote($siteAccess, $groupConfig));
    }

    public function voteProvider(): array
    {
        return [
            ['cro', ['cro', 'eng'], true],
            ['eng', ['cro', 'eng'], true],
            ['admin', ['cro', 'eng'], VoterInterface::ABSTAIN],
            ['extra', ['cro', 'eng'], VoterInterface::ABSTAIN],
        ];
    }
}
