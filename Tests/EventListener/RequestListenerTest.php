<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests\EventListener;

use eZ\Publish\Core\MVC\Symfony\SiteAccess;
use Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\MatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use PHPUnit\Framework\TestCase;

class RequestListenerTest extends TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $matcherMock;

    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\EventListener\RequestListener
     */
    protected $listener;

    public function setUp()
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
        $this->assertEquals(
            array(KernelEvents::REQUEST => array('onKernelRequest', 31)),
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
            ->expects($this->once())
            ->method('isAllowed')
            ->with($this->equalTo('eng'), $this->equalTo(array('cro')))
            ->will($this->returnValue(true));

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

        $request->attributes->set('allowed_siteaccess', array('cro', 'eng'));
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects($this->once())
            ->method('isAllowed')
            ->with($this->equalTo('eng'), $this->equalTo(array('cro', 'eng')))
            ->will($this->returnValue(true));

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

        $request->attributes->set('allowed_siteaccess', array('cro', 'eng'));
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects($this->once())
            ->method('isAllowed')
            ->with($this->equalTo('eng'), $this->equalTo(array('cro', 'eng')))
            ->will($this->returnValue(false));

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

        $request->attributes->set('allowed_siteaccess', array('cro', 'eng'));
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects($this->never())
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

        $request->attributes->set('allowed_siteaccess', array('cro', 'eng'));

        $this->matcherMock
            ->expects($this->never())
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
            ->expects($this->never())
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

        $request->attributes->set('allowed_siteaccess', array());
        $request->attributes->set('siteaccess', new SiteAccess('eng'));

        $this->matcherMock
            ->expects($this->never())
            ->method('isAllowed');

        $event = new GetResponseEvent($kernelMock, $request, HttpKernelInterface::MASTER_REQUEST);
        self::assertNull($this->listener->onKernelRequest($event));
    }
}
