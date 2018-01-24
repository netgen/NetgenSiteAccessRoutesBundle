<?php

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

    public function setUp()
    {
        $this->voter = new SiteAccessGroupVoter(
            array(
                'eng' => array('frontend'),
                'cro' => array('frontend'),
                'admin' => array('backend'),
            )
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
        $this->assertEquals($vote, $this->voter->vote($siteAccess, $groupConfig));
    }

    public function voteProvider()
    {
        return array(
            array('cro', array('cro', 'backend'), VoterInterface::ABSTAIN),
            array('eng', array('cro', 'backend'), VoterInterface::ABSTAIN),
            array('admin', array('cro', 'backend'), true),
            array('extra', array('cro', 'backend'), VoterInterface::ABSTAIN),
        );
    }
}
