<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher\Voter;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\SiteAccessVoter;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

#[CoversClass(SiteAccessVoter::class)]
final class SiteAccessVoterTest extends TestCase
{
    private SiteAccessVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new SiteAccessVoter();
    }

    #[DataProvider('provideVoteCases')]
    public function testVote(string $siteAccess, array $groupConfig, ?bool $vote): void
    {
        self::assertSame($vote, $this->voter->vote($siteAccess, $groupConfig));
    }

    public static function provideVoteCases(): iterable
    {
        return [
            ['cro', ['cro', 'eng'], true],
            ['eng', ['cro', 'eng'], true],
            ['admin', ['cro', 'eng'], VoterInterface::ABSTAIN],
            ['extra', ['cro', 'eng'], VoterInterface::ABSTAIN],
        ];
    }
}
