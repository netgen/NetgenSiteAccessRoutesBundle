<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher\Voter;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\DefaultSiteAccessVoter;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use Netgen\Bundle\SiteAccessRoutesBundle\Tests\TestCase;

class DefaultSiteAccessVoterTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\DefaultSiteAccessVoter
     */
    protected $voter;

    public function setUp()
    {
        $this->voter = new DefaultSiteAccessVoter('eng');
    }

    /**
     * @param string $siteAccess
     * @param array $groupConfig
     * @param bool $vote
     *
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\DefaultSiteAccessVoter::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\DefaultSiteAccessVoter::vote
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
            array('cro', array('_default'), VoterInterface::ABSTAIN),
            array('eng', array('_default'), true),
            array('extra', array('_default'), VoterInterface::ABSTAIN),
            array('cro', array('cro'), VoterInterface::ABSTAIN),
            array('eng', array('cro'), VoterInterface::ABSTAIN),
            array('extra', array('cro'), VoterInterface::ABSTAIN),
        );
    }
}
