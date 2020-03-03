<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Tests;


use App\Kernel;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class KernelTest
 * @package App\Tests
 */
class KernelTest extends TestCase
{
    /** @var KernelInterface */
    protected $kernel;

    protected function setUp()
    {
        parent::setUp();
        $this->kernel = new Kernel(getenv('APP_ENV'), getenv('APP_ENV'));
    }

    public function testCallRegisterBundlesMethod()
    {
        $this->assertInstanceOf(\Traversable::class, $this->kernel->registerBundles());
    }

    public function testCallProjectDirMethod()
    {
        $this->assertIsString($this->kernel->getProjectDir());
    }
}