<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests\Controller;

use App\Controller\HomeController;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * Class HomeControllerTest
 * @package App\Tests\Controller
 */
class HomeControllerTest extends WebTestCase
{
    //public function testGuestHomePage()
    //{
    //    /** @var KernelBrowser $client */
    //    $client = static::createClient();
    //    $client->request(Request::METHOD_GET, '/');
    //    $this->assertSame(Response::HTTP_FOUND, $client->getResponse()->getStatusCode()); // Redirect
    //    $this->assertSame('http://localhost/login', $client->getResponse()->headers->get('Location'));
    //}
    //
    //public function testSuccess(): void
    //{
    //    /** @var KernelBrowser $client */
    //    $client = static::createClient([], [
    //        'PHP_AUTH_USER' => 'demo@example.com',
    //        'PHP_AUTH_PW' => 'secret'
    //    ]);
    //
    //    /** @var Crawler $crawler */
    //    $crawler = $client->request(Request::METHOD_GET, '/');
    //    $this->assertSame(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    //}

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
        $response = $controller->home($request->reveal());

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}