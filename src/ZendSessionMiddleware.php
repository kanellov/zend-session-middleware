<?php
/**
 * Zend Session Middleware.
 *
 * @link https://github.com/kanellov/zend-session-middleware for the canonical source repository
 *
 * @copyright Copyright (c) 2017 Vassilis Kanellopoulos <contact@kanellov.com>
 * @license GNU GPLv3 http://www.gnu.org/licenses/gpl-3.0-standalone.html
 */

namespace Knlv\Middleware;

use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Session\Container;
use Zend\Session\SessionManager;

class ZendSessionMiddleware implements MiddlewareInterface
{
    /**
     * @var SessionManager
     */
    private $session;

    /**
     * ZendSessionMiddleware constructor.
     * @param SessionManager $session
     */
    public function __construct(SessionManager $session)
    {
        $this->session = $session;
    }

    /**
     * Middleware process
     * @param ServerRequestInterface $request
     * @param DelegateInterface $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $this->session->start();
        Container::setDefaultManager($this->session);

        $container = new Container('session_initialized');

        if (!isset($container->init)) {
            $this->session->regenerateId(true);
            $container->init = true;
        }
        return $delegate->process($request);
    }
}
