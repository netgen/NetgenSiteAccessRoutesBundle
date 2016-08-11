<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;
use Netgen\Bundle\SiteAccessRoutesBundle\Tests\TestCase;

class MatcherIntegrationTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher
     */
    protected $matcher;

    public function setUp()
    {
        $this->matcher = new Matcher(
            array(
                new Voter\DefaultSiteAccessVoter('eng'),
                new Voter\SiteAccessVoter(),
                new Voter\SiteAccessGroupVoter(
                    array(
                        'eng' => array('frontend'),
                        'cro' => array('frontend'),
                        'admin' => array('backend'),
                    )
                ),
            )
        );
    }

    /**
     * @param string $siteAccess
     * @param array $routeConfig
     * @param bool $isAllowed
     *
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher::isAllowed
     *
     * @dataProvider isAllowedProvider
     */
    public function testIsAllowed($siteAccess, array $routeConfig, $isAllowed)
    {
        $this->assertEquals($isAllowed, $this->matcher->isAllowed($siteAccess, $routeConfig));
    }

    public function isAllowedProvider()
    {
        return array(
            array('eng', array('eng'), true),
            array('cro', array('eng'), false),
            array('extra', array('eng'), false),

            array('eng', array('cro'), false),
            array('cro', array('cro'), true),
            array('extra', array('cro'), false),

            array('eng', array('_default'), true),
            array('cro', array('_default'), false),
            array('extra', array('_default'), false),

            array('eng', array('eng', 'backend'), true),
            array('cro', array('eng', 'backend'), false),
            array('admin', array('eng', 'backend'), true),
            array('extra', array('eng', 'backend'), false),

            array('eng', array('_default', 'backend'), true),
            array('cro', array('_default', 'backend'), false),
            array('admin', array('_default', 'backend'), true),
            array('extra', array('_default', 'backend'), false),

            array('eng', array('_default', 'cro'), true),
            array('cro', array('_default', 'cro'), true),
            array('admin', array('_default', 'cro'), false),
            array('extra', array('_default', 'cro'), false),
        );
    }
}
