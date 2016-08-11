<?php

namespace Netgen\Bundle\SiteAccessRoutesBundle\Tests;

use PHPUnit_Framework_TestCase;

/**
 * This class is a compatibility layer for using
 * PHPUnit 4.8 on PHP 5.5 and PHPUnit 5.5 on PHP >= 5.6.
 */
class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * Returns a test double for the specified class.
     *
     * @param string $originalClassName
     *
     * @throws \PHPUnit_Framework_Exception
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createMock($originalClassName)
    {
        if (method_exists(PHPUnit_Framework_TestCase::class, 'createMock')) {
            return parent::createMock($originalClassName);
        }

        return $this
            ->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->getMock();
    }

    /**
     * Returns a partial test double for the specified class.
     *
     * @param string $originalClassName
     * @param array $methods
     *
     * @throws \PHPUnit_Framework_Exception
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function createPartialMock($originalClassName, array $methods)
    {
        if (method_exists(PHPUnit_Framework_TestCase::class, 'createPartialMock')) {
            return parent::createPartialMock($originalClassName, $methods);
        }

        return $this
            ->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            ->disableOriginalClone()
            ->disableArgumentCloning()
            ->setMethods($methods)
            ->getMock();
    }
}
