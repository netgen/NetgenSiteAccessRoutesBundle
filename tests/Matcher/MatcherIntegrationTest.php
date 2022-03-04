<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter;
use PHPUnit\Framework\TestCase;

final class MatcherIntegrationTest extends TestCase
{
    private Matcher $matcher;

    protected function setUp(): void
    {
        $this->matcher = new Matcher(
            [
                new Voter\DefaultSiteAccessVoter('eng'),
                new Voter\SiteAccessVoter(),
                new Voter\SiteAccessGroupVoter(
                    [
                        'eng' => ['frontend'],
                        'cro' => ['frontend'],
                        'admin' => ['backend'],
                    ]
                ),
            ]
        );
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher::isAllowed
     *
     * @dataProvider isAllowedProvider
     */
    public function testIsAllowed(string $siteAccess, array $routeConfig, bool $isAllowed): void
    {
        self::assertSame($isAllowed, $this->matcher->isAllowed($siteAccess, $routeConfig));
    }

    public function isAllowedProvider(): array
    {
        return [
            ['eng', ['eng'], true],
            ['cro', ['eng'], false],
            ['extra', ['eng'], false],

            ['eng', ['cro'], false],
            ['cro', ['cro'], true],
            ['extra', ['cro'], false],

            ['eng', ['_default'], true],
            ['cro', ['_default'], false],
            ['extra', ['_default'], false],

            ['eng', ['eng', 'backend'], true],
            ['cro', ['eng', 'backend'], false],
            ['admin', ['eng', 'backend'], true],
            ['extra', ['eng', 'backend'], false],

            ['eng', ['_default', 'backend'], true],
            ['cro', ['_default', 'backend'], false],
            ['admin', ['_default', 'backend'], true],
            ['extra', ['_default', 'backend'], false],

            ['eng', ['_default', 'cro'], true],
            ['cro', ['_default', 'cro'], true],
            ['admin', ['_default', 'cro'], false],
            ['extra', ['_default', 'cro'], false],
        ];
    }
}
