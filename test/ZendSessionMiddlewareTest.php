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

use Interop\Http\ServerMiddleware\DelegateInterface;
use Knlv\Middleware\ZendSessionMiddleware;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Session\Storage\ArrayStorage;

class ZendSessionMiddlewareTest extends TestCase
{
    /**
     * @var ObjectProphecy
     */
    private $session;

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @var DelegateInterface
     */
    private $delegate;

    protected function setUp()
    {
        $storage = new ArrayStorage();
        $this->session = $this->prophesize(SessionManager::class);
        $this->session->start()->willReturn(true);
        $this->session->start()->shouldBeCalled();
        $this->session->getStorage()->willReturn($storage);
        $this->session->regenerateId(true)->willReturn(true);

        $request = $this->prophesize(ServerRequestInterface::class);
        $this->request = $request->reveal();
        $response = $this->prophesize(ResponseInterface::class);
        $this->response = $response->reveal();
        $delegate = $this->prophesize(DelegateInterface::class);
        $delegate->process(Argument::any())->will(function () use ($response) {
            return $response;
        });
        $delegate->process(Argument::any())->shouldBeCalled();
        $this->delegate = $delegate->reveal();
    }

    public function testProcess_notStartedBefore()
    {

        $this->session->regenerateId(true)->shouldBeCalled();
        $session = $this->session->reveal();

        $container = new Container('session_initialized', $session);
        $this->assertNull($container->init);

        $middleware = new ZendSessionMiddleware($session);
        $result = $middleware->process($this->request, $this->delegate);

        $this->assertSame($this->response, $result);
        $this->assertTrue($container->init);
    }

    public function testProcess_startedBefore()
    {
        $this->session->regenerateId(true)->shouldNotBeCalled();
        $session = $this->session->reveal();

        $container = new Container('session_initialized', $session);
        $container->init = true;

        $middleware = new ZendSessionMiddleware($session);
        $result = $middleware->process($this->request, $this->delegate);

        $this->assertSame($this->response, $result);
    }
}
