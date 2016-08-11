<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher\Voter;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessVoter;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use Netgen\Bundle\SiteAccessRoutesBundle\Tests\TestCase;

class SiteAccessVoterTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessVoter
     */
    protected $voter;

    public function setUp()
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
        $this->assertEquals($vote, $this->voter->vote($siteAccess, $groupConfig));
    }

    public function voteProvider()
    {
        return array(
            array('cro', array('cro', 'eng'), true),
            array('eng', array('cro', 'eng'), true),
            array('admin', array('cro', 'eng'), VoterInterface::ABSTAIN),
            array('extra', array('cro', 'eng'), VoterInterface::ABSTAIN),
        );
    }
}
