<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher\Voter;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessGroupVoter;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use PHPUnit\Framework\TestCase;

class SiteAccessGroupVoterTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessGroupVoter
     */
    protected $voter;

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
     * @param string $siteAccess
     * @param array $groupConfig
     * @param bool $vote
     *
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessGroupVoter::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessGroupVoter::vote
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
            ['cro', ['cro', 'backend'], VoterInterface::ABSTAIN],
            ['eng', ['cro', 'backend'], VoterInterface::ABSTAIN],
            ['admin', ['cro', 'backend'], true],
            ['extra', ['cro', 'backend'], VoterInterface::ABSTAIN],
        ];
    }
}
