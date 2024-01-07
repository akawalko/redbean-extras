<?php

declare(strict_types=1);

namespace Akawalko\RedbeanExtras\Tests\BeanHelper;

use Akawalko\RedbeanExtras\BeanHelper\BeanHelper;
use Akawalko\RedbeanExtras\BeanHelper\BeanHelperFactory;
use Akawalko\RedbeanExtras\Finder\ModelClassFinder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;

class BeanHelperFactoryTest extends TestCase
{
    /** @test */
    public function when_the_factory_was_created_with_cache_it_will_try_to_call_cache_method(): void
    {
        $cache = $this->createMock(PhpArrayAdapter::class);
        $cache
            ->expects($this->once())
            ->method('get')
            ->willReturn([
                'user' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\User\User',
                'user_setting' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\User\UserSetting',
            ]);

        // Since I created a mock, the ModelClassFinder::findInDirectory method will not be called in the closure
        $modelClassFinder = $this->createMock(ModelClassFinder::class);
        $modelClassFinder
            ->expects($this->never())
            ->method('findInDirectory');

        $factory = new BeanHelperFactory($modelClassFinder, $cache, 5);
        $beanHelper = $factory->create(__DIR__ . '/../ExampleModelClasses/src/Module/User');

        $this->assertInstanceOf(BeanHelper::class, $beanHelper);
    }
}
