<?php

declare(strict_types=1);

namespace Netgen\Bundle\SiteAccessRoutesBundle\EventListener;

use eZ\Publish\Core\MVC\Symfony\SiteAccess;
use Netgen\Bundle\SiteAccessRoutesBundle\Matcher\MatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class RequestListener implements EventSubscriberInterface
{
    public const ALLOWED_SITEACCESSES_KEY = 'allowed_siteaccess';

    /**
     * @var \Netgen\Bundle\SiteAccessRoutesBundle\Matcher\MatcherInterface
     */
    protected $matcher;

    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Needs to execute right after Symfony RouterListener (priority of 32)
            KernelEvents::REQUEST => ['onKernelRequest', 31],
        ];
    }

    /**
     * Throws an exception if current route is not allowed in current siteaccess.
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
            return;
        }

        $requestAttributes = $event->getRequest()->attributes;

        $currentSiteAccess = $requestAttributes->get('siteaccess');
        $allowedSiteAccesses = $requestAttributes->get(self::ALLOWED_SITEACCESSES_KEY);

        // We allow the route if no current siteaccess is set or
        // no allowed siteaccesses (meaning all are allowed) are defined
        if (!$currentSiteAccess instanceof SiteAccess || empty($allowedSiteAccesses)) {
            return;
        }

        if (!is_array($allowedSiteAccesses)) {
            $allowedSiteAccesses = [$allowedSiteAccesses];
        }

        if ($this->matcher->isAllowed($currentSiteAccess->name, $allowedSiteAccesses)) {
            return;
        }

        throw new NotFoundHttpException('Route is not allowed in current siteaccess');
    }
}
