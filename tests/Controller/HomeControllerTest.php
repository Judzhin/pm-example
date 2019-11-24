<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\Controller;

use App\Controller\HomeController;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class HomeControllerTest
 * @package App\Tests\Controller
 */
class HomeControllerTest extends TestCase
{
    public function testCallHomeMethod()
    {
        /** @var  $controller */
        $controller = new HomeController;

        $container = $this->prophesize(ContainerInterface::class);

        $container
            ->has('parameter_bag')
            ->willReturn(true);

        $container
            ->get('parameter_bag')
            ->willReturn($this->prophesize(ContainerBag::class));

        $container
            ->has('twig')
            ->willReturn(true);

        /** @var  $environment */
        $environment = $this->prophesize(Environment::class);
        $environment->render(
            Argument::type('string'),
            Argument::type('array')
        )->willReturn('');

        $container
            ->get('twig')
            ->willReturn($environment);

        /** @var Request $request */
        $request = $this->prophesize(Request::class);

        $controller
            ->setContainer($container->reveal());

        /** @var Response $response */
        $response = $controller->home(
            $request->reveal()
        );

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}