<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Middleware;


use LogicException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use SplStack;

/**
 * Class Dispatcher
 * @package FastD\Middleware
 */
class Dispatcher
{
    /**
     * @var SplStack
     */
    protected $stack;

    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * Dispatcher constructor.
     * @param $stack
     */
    public function __construct(array $stack = [])
    {
        $this->stack = new SplStack();

        foreach ($stack as $value) {
            $this->before($value);
        }
    }

    /**
     * @deprecated
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function withAddMiddleware(MiddlewareInterface $middleware)
    {
        $this->stack->push($middleware);

        return $this;
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function after(MiddlewareInterface $middleware)
    {
        $this->stack->unshift($middleware);

        return $this;
    }

    /**
     * @param MiddlewareInterface $middleware
     * @return $this
     */
    public function before(MiddlewareInterface $middleware)
    {
        $this->stack->push($middleware);

        return $this;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $resolved = $this->resolve();

        return $resolved->process($request);
    }

    /**
     * @return DelegateInterface
     */
    private function resolve()
    {
        if (!$this->stack->isEmpty()) {
            return new Delegate(function (ServerRequestInterface $request) {
                $middleware = $this->stack->shift();
                $response = $middleware->process($request, $this->resolve());
                unset($middleware);
                return $response;
            });
        }

        return new Delegate(function () {
            throw new LogicException('unresolved request: middleware stack exhausted with no result');
        });
    }
}