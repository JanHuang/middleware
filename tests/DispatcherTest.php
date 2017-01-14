<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */


use FastD\Http\ServerRequest;
use FastD\Middleware\Dispatcher;


class DispatcherTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        include_once __DIR__ . '/middleware/ServerMiddleware.php';
    }

    public function testDispatcher()
    {
        $dispatcher = new Dispatcher([
            new \ServerMiddleware(),
        ]);

        $dispatcher->dispatch(new ServerRequest('GET', '/'));

        $this->expectOutputString('hello world');
    }
}
