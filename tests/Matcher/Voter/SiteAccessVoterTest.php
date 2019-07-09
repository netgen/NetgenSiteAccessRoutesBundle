<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher\Voter;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessVoter;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use PHPUnit\Framework\TestCase;

class SiteAccessVoterTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessVoter
     */
    protected $voter;

    protected function setUp(): void
    {
        $this->voter = new SiteAccessVoter();
    }

    /**
     * @param string $siteAccess
     * @param array $groupConfig
     * @param bool $vote
     *
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessVoter::vote
     *
     * @dataProvider voteProvider
     */
    public function testVote($siteAccess, array $groupConfig, $vote)
    {
        self::assertSame($vote, $this->voter->vote($siteAccess, $groupConfig));
    }

    public function voteProvider()
    {
        return [
            ['cro', ['cro', 'eng'], true],
            ['eng', ['cro', 'eng'], true],
            ['admin', ['cro', 'eng'], VoterInterface::ABSTAIN],
            ['extra', ['cro', 'eng'], VoterInterface::ABSTAIN],
        ];
    }
}
