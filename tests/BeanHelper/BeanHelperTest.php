<?php

declare(strict_types=1);

namespace Akawalko\RedbeanExtras\Tests\BeanHelper;

use Akawalko\RedbeanExtras\BeanHelper\BeanHelper;
use PHPUnit\Framework\TestCase;
use RedBeanPHP\OODB;
use RedBeanPHP\R;

class BeanHelperTest extends TestCase
{
    protected static $classes = [
        'user' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\User\User',
        'user_setting' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\User\UserSetting',
        'permission' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\Rbac\Permission',
        'role' => 'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\Rbac\Role',
    ];

    protected static $userNo1 = [
        '_type' => 'user',
        'id' => '1',
        'firstname' => 'John',
        'lastname' => 'Doe',
        'gender' => 'male',
        'birthdate' => '1980-12-08',
    ];

    protected function setUp(): void
    {
        R::setRedBean($this->createRedbeanOODBMock(self::$classes));
    }

    /** @test */
    public function successfully_loads_model_class_and_runs_its_method(): void
    {
        $user = R::dispense(self::$userNo1);
        $modelObject = $user->box();

        $this->assertInstanceOf(
            'Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\User\User',
            $modelObject
        );
        $this->assertEquals(true, $modelObject->canBuyAlcohol());
    }

    /** @test */
    public function fails_to_load_model_class_when_no_classes_was_passed_to_helper_constructor(): void
    {
        R::setRedBean($this->createRedbeanOODBMock());
        $user = R::dispense(self::$userNo1);
        $modelObject = $user->box();

        $this->assertNull($modelObject);
    }

    protected function createRedbeanOODBMock(array $redbeanTypeIndexedClassNames = []): OODB
    {
        $writer = $this->createMock(\RedBeanPHP\QueryWriter\MySQL::class);
        $OODB = new OODB($writer, true);
        $OODB->setBeanHelper(new BeanHelper($redbeanTypeIndexedClassNames));
        return $OODB;
    }
}
