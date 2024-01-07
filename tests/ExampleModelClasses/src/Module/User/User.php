<?php

declare(strict_types=1);

namespace Akawalko\RedbeanExtras\Tests\ExampleModelClasses\src\Module\User;

use DateTime;

// when you use php > 8.0 you can extend \RedBeanPHP\TypedModel
class User extends \RedBeanPHP\SimpleModel
{
    public const AGE_OF_CONSENT_TO_PURCHASE_ALCOHOL = 18;

    public function canBuyAlcohol(): bool
    {
        $now = new DateTime();
        $birthdate = new DateTime($this->bean->birthdate);
        $dateInterval = $now->diff($birthdate);

        return (int) $dateInterval->format('%Y') > self::AGE_OF_CONSENT_TO_PURCHASE_ALCOHOL;
    }
}
