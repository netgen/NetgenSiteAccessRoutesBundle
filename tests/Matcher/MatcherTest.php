<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\Matcher;

use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Voter\VoterInterface;
use PHPUnit\Framework\TestCase;

final class MatcherTest extends TestCase
{
    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher
     */
    protected $matcher;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject[]
     */
    protected $voterMocks;

    protected function setUp(): void
    {
        $this->voterMocks = [
            $this->createMock(VoterInterface::class),
            $this->createMock(VoterInterface::class),
            $this->createMock(VoterInterface::class),
        ];

        $this->matcher = new Matcher($this->voterMocks);
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\Matcher::isAllowed
     *
     * @dataProvider isAllowedProvider
     */
    public function testIsAllowed(array $voterResults, bool $isAllowed): void
    {
        foreach ($this->voterMocks as $index => $voter) {
            $voter
                ->expects(self::any())
                ->method('vote')
                ->willReturn($voterResults[$index]);
        }

        self::assertSame($isAllowed, $this->matcher->isAllowed('cro', []));
    }

    public function isAllowedProvider(): array
    {
        return [
            [[true, true, true], true],
            [[true, true, false], true],
            [[true, false, true], true],
            [[true, false, false], true],
            [[false, true, true], false],
            [[false, true, false], false],
            [[false, false, true], false],
            [[false, false, false], false],
            [[VoterInterface::ABSTAIN, true, true], true],
            [[VoterInterface::ABSTAIN, true, false], true],
            [[VoterInterface::ABSTAIN, false, true], false],
            [[VoterInterface::ABSTAIN, false, false], false],
            [[true, VoterInterface::ABSTAIN, true], true],
            [[true, VoterInterface::ABSTAIN, false], true],
            [[false, VoterInterface::ABSTAIN, true], false],
            [[false, VoterInterface::ABSTAIN, false], false],
            [[true, true, VoterInterface::ABSTAIN], true],
            [[true, false, VoterInterface::ABSTAIN], true],
            [[false, true, VoterInterface::ABSTAIN], false],
            [[false, false, VoterInterface::ABSTAIN], false],
            [[VoterInterface::ABSTAIN, VoterInterface::ABSTAIN, true], true],
            [[VoterInterface::ABSTAIN, VoterInterface::ABSTAIN, false], false],
            [[VoterInterface::ABSTAIN, true, VoterInterface::ABSTAIN], true],
            [[VoterInterface::ABSTAIN, false, VoterInterface::ABSTAIN], false],
            [[true, VoterInterface::ABSTAIN, VoterInterface::ABSTAIN], true],
            [[false, VoterInterface::ABSTAIN, VoterInterface::ABSTAIN], false],
            [[VoterInterface::ABSTAIN, VoterInterface::ABSTAIN, VoterInterface::ABSTAIN], false],
        ];
    }
}
