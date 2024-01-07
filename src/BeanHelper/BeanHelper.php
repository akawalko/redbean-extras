<?php

declare(strict_types=1);

namespace Akawalko\RedbeanExtras\BeanHelper;

use RedBeanPHP\BeanHelper\SimpleFacadeBeanHelper;
use RedBeanPHP\OODBBean;

class BeanHelper extends SimpleFacadeBeanHelper
{
    protected array $redbeanTypeIndexedClassNames;

    public function __construct(array $redbeanTypeIndexedClassNames = [])
    {
        $this->redbeanTypeIndexedClassNames = $redbeanTypeIndexedClassNames;
    }

    public function getModelForBean(OODBBean $bean)
    {
        $type = $bean->getMeta('type');

        if (!empty($this->redbeanTypeIndexedClassNames) && isset($this->redbeanTypeIndexedClassNames[$type])) {
            $modelName = $this->redbeanTypeIndexedClassNames[$type]; // already contain type in its path
            /** @var \RedBeanPHP\SimpleModel $obj */
            $obj = self::factory($modelName);
            $obj->loadBean($bean);
            return $obj;
        }

        return parent::getModelForBean($bean);
    }
}
