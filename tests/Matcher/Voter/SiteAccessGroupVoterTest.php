<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher\Voter;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessGroupVoter;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use PHPUnit\Framework\TestCase;

final class SiteAccessGroupVoterTest extends TestCase
{
    private SiteAccessGroupVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new SiteAccessGroupVoter(
            [
                'eng' => ['frontend'],
                'cro' => ['frontend'],
                'admin' => ['backend'],
            ]
        );
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessGroupVoter::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessGroupVoter::vote
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
            ['cro', ['cro', 'backend'], VoterInterface::ABSTAIN],
            ['eng', ['cro', 'backend'], VoterInterface::ABSTAIN],
            ['admin', ['cro', 'backend'], true],
            ['extra', ['cro', 'backend'], VoterInterface::ABSTAIN],
        ];
    }
}
