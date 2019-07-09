<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\EventListener;

use eZ\Publish\Core\MVC\Symfony\SiteAccess;
use Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\MatcherInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestListenerTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    protected $matcherMock;

    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener
     */
    protected $listener;

    protected function setUp(): void
    {
        $this->matcherMock = $this->createMock(MatcherInterface::class);

        $this->listener = new RequestListener($this->matcherMock);
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::__construct
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::getSubscribedEvents
     */
    public function testGetSubscribedEvents()
    {
        self::assertSame(
            [KernelEvents::REQUEST => ['onKernelRequest', 31]],
            $this->listener->getSubscribedEvents()
        );
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequest()
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

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestWithConfigArray()
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

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testOnKernelRequestThrowsNotFoundHttpException()
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', ['cro', 'eng']);
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::once())
            ->method('isAllowed')
            ->with(self::equalTo('eng'), self::equalTo(['cro', 'eng']))
            ->willReturn(false);

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        $this->listener->onKernelRequest($event);
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestInSubRequest()
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', ['cro', 'eng']);
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::never())
            ->method('isAllowed');

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::SUB_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestWithNoSiteAccess()
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', ['cro', 'eng']);

        $this->matcherMock
            ->expects(self::never())
            ->method('isAllowed');

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestWithNoConfig()
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::never())
            ->method('isAllowed');

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }

    /**
     * @covers \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener::onKernelRequest
     */
    public function testOnKernelRequestWithEmptyConfig()
    {
        $kernelMock = $this->createMock(HttpKernelInterface::class);
        $request = Request::create('/');

        $request->attributes->set('allowed_siteaccess', []);
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects(self::never())
            ->method('isAllowed');

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }
}
