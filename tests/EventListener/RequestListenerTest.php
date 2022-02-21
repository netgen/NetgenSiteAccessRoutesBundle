<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\EventListener;

use Ibexa\Core\MVC\Symfony\SiteAccess;
use Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\MatcherInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

final class RequestListenerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $matcherMock;

    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener
     */
    private $listener;

    protected function setUp(): void
    {
        $this->matcherMock = $this->createMock(MatcherInterface::class);

        $this->listener = new RequestListener($this->matcherMock);
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents(): void
    {
        self::assertSame(
            [KernelEvents::REQUEST => ['onKernelRequest', 31]],
            $this->listener::getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequest(): void
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', 'cro');
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::once())
            ->method('isAllowed')
            ->with(self::equalTo('eng'), self::equalTo(['cro']))
            ->willReturn(true);

        $event = new RequestEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestWithConfigArray(): void
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', ['cro', 'eng']);
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::once())
            ->method('isAllowed')
            ->with(self::equalTo('eng'), self::equalTo(['cro', 'eng']))
            ->willReturn(true);

        $event = new RequestEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestThrowsNotFoundHttpException(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', ['cro', 'eng']);
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::once())
            ->method('isAllowed')
            ->with(self::equalTo('eng'), self::equalTo(['cro', 'eng']))
            ->willReturn(false);

        $event = new RequestEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        $this->listener->onKernelRequest($event);
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestInSubRequest(): void
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', ['cro', 'eng']);
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::never())
            ->method('isAllowed');

        $event = new RequestEvent($kernelMock, $request, HttpKernelInterface::SUB_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestWithNoSiteAccess(): void
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', ['cro', 'eng']);

        $this->matcherMock
            ->expects(self::never())
            ->method('isAllowed');

        $event = new RequestEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestWithNoConfig(): void
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::never())
            ->method('isAllowed');

        $event = new RequestEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestWithEmptyConfig(): void
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', []);
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::never())
            ->method('isAllowed');

        $event = new RequestEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }
}
