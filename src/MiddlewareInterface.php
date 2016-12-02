<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface MiddlewareInterface
 *
 * @package FastD\Middleware
 */
interface MiddlewareInterface
{
    /**
     * @param RequestInterface $request
     * @param DelegateInterface $next
     * @return ResponseInterface
     */
    public function __invoke(RequestInterface $request, DelegateInterface $next);
}