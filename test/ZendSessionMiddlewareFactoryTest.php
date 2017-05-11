<?php
/**
 * Zend Session Middleware.
 *
 * @link https://github.com/kanellov/zend-session-middleware for the canonical source repository
 *
 * @copyright Copyright (c) 2017 Vassilis Kanellopoulos <contact@kanellov.com>
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace KnlvTest\Middleware;

use Interop\Container\ContainerInterface;
use Knlv\Middleware\ZendSessionMiddleware;
use Knlv\Middleware\ZendSessionMiddlewareFactory;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Zend\Session\SessionManager;

class ZendSessionMiddlewareFactoryTest extends TestCase
{
    /**
     * @var ObjectProphecy
     */
    private $container;

    /**
     * Setup container with SessionManager
     */
    protected function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $session = $this->prophesize(SessionManager::class);
        $this->container->get(SessionManager::class)->willReturn($session->reveal());
    }

    /**
     * Test ZendSessionMiddlewareFactory::__invoke(ContainerInterface) method
     */
    public function testFactory()
    {
        $factory = new ZendSessionMiddlewareFactory();
        $middleware = $factory($this->container->reveal());

        $this->assertInstanceOf(ZendSessionMiddleware::class, $middleware);
    }
}
