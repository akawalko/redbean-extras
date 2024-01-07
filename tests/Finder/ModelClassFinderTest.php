<?php

declare(strict_types=1);

namespace Akawalko\RedbeanExtras\Tests\Finder;

use Akawalko\RedbeanExtras\Finder\ModelClassFinder;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class ModelClassFinderTest extends TestCase
{
    /** @test */
    public function find_model_classes_in_separate_modules_directories(): void
    {
        $finder = new ModelClassFinder();
        $userModuleModelClasses = $finder->findInDirectory(
            __DIR__ . '/../ExampleModelClasses/src/Module/User'
        );
        $this->assertEquals(
            [
                'user' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\User\User',
                'user_setting' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\User\UserSetting',
            ],
            $userModuleModelClasses
        );

        $rbacModuleModelClasses = $finder->findInDirectory(
            __DIR__ . '/../ExampleModelClasses/src/Module/Rbac'
        );
        $this->assertEquals(
            [
                'permission' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\Rbac\Permission',
                'role' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\Rbac\Role',
            ],
            $rbacModuleModelClasses
        );
    }

    /** @test */
    public function upper_camel_case_successfully_converted_in_snake_case(): void
    {
        $finder = new ModelClassFinder();
        $method = new ReflectionMethod($finder, 'convertToSnakeCase');
        $method->setAccessible(true);

        $this->assertEquals(
            'user_role',
            $method->invoke($finder, 'UserRole')
        );
    }
}
