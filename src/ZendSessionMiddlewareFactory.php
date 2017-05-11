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

use Interop\Container\ContainerInterface;
use Zend\Session\SessionManager;

class ZendSessionMiddlewareFactory
{
    public function __invoke(ContainerInterface $c)
    {
        $session = $c->get(SessionManager::class);
        return new ZendSessionMiddleware($session);
    }
}
