<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use Netgen\Bundle\SiteAccessRoutesBundle\Tests\TestCase;

class MatcherTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher
     */
    protected $matcher;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject[]
     */
    protected $voterMocks;

    public function setUp()
    {
        $this->voterMocks = array(
            $this->createMock(VoterInterface::class),
            $this->createMock(VoterInterface::class),
            $this->createMock(VoterInterface::class),
        );

        $this->matcher = new Matcher($this->voterMocks);
    }

    /**
     * @param array $voterResults
     * @param bool $isAllowed
     *
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher::isAllowed
     *
     * @dataProvider isAllowedProvider
     */
    public function testIsAllowed(array $voterResults, $isAllowed)
    {
        foreach ($this->voterMocks as $index => $voter) {
            $voter
                ->expects($this->any())
                ->method('vote')
                ->will($this->returnValue($voterResults[$index]));
        }

        $this->assertEquals($isAllowed, $this->matcher->isAllowed('cro', array()));
    }

    public function isAllowedProvider()
    {
        return array(
            array(array(true, true, true), true),
            array(array(true, true, false), true),
            array(array(true, false, true), true),
            array(array(true, false, false), true),
            array(array(false, true, true), false),
            array(array(false, true, false), false),
            array(array(false, false, true), false),
            array(array(false, false, false), false),
            array(array(VoterInterface::ABSTAIN, true, true), true),
            array(array(VoterInterface::ABSTAIN, true, false), true),
            array(array(VoterInterface::ABSTAIN, false, true), false),
            array(array(VoterInterface::ABSTAIN, false, false), false),
            array(array(true, VoterInterface::ABSTAIN, true), true),
            array(array(true, VoterInterface::ABSTAIN, false), true),
            array(array(false, VoterInterface::ABSTAIN, true), false),
            array(array(false, VoterInterface::ABSTAIN, false), false),
            array(array(true, true, VoterInterface::ABSTAIN), true),
            array(array(true, false, VoterInterface::ABSTAIN), true),
            array(array(false, true, VoterInterface::ABSTAIN), false),
            array(array(false, false, VoterInterface::ABSTAIN), false),
            array(array(VoterInterface::ABSTAIN, VoterInterface::ABSTAIN, true), true),
            array(array(VoterInterface::ABSTAIN, VoterInterface::ABSTAIN, false), false),
            array(array(VoterInterface::ABSTAIN, true, VoterInterface::ABSTAIN), true),
            array(array(VoterInterface::ABSTAIN, false, VoterInterface::ABSTAIN), false),
            array(array(true, VoterInterface::ABSTAIN, VoterInterface::ABSTAIN), true),
            array(array(false, VoterInterface::ABSTAIN, VoterInterface::ABSTAIN), false),
            array(array(VoterInterface::ABSTAIN, VoterInterface::ABSTAIN, VoterInterface::ABSTAIN), false),
        );
    }
}
