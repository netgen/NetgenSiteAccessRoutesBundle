<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher\Voter;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\DefaultSiteAccessVoter;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(DefaultSiteAccessVoter::class)]
final class DefaultSiteAccessVoterTest extends TestCase
{
    private DefaultSiteAccessVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new DefaultSiteAccessVoter('eng');
    }

    #[DataProvider('provideVoteCases')]
    public function testVote(string $siteAccess, array $groupConfig, ?bool $vote): void
    {
        self::assertSame($vote, $this->voter->vote($siteAccess, $groupConfig));
    }

    public static function provideVoteCases(): iterable
    {
        return [
            ['cro', ['_default'], VoterInterface::ABSTAIN],
            ['eng', ['_default'], true],
            ['extra', ['_default'], VoterInterface::ABSTAIN],
            ['cro', ['cro'], VoterInterface::ABSTAIN],
            ['eng', ['cro'], VoterInterface::ABSTAIN],
            ['extra', ['cro'], VoterInterface::ABSTAIN],
        ];
    }
}
