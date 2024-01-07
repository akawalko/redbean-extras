<?php

declare(strict_types=1);

namespace Akawalko\RedbeanExtras\BeanHelper;

use Akawalko\RedbeanExtras\Finder\ModelClassFinder;
use RedBeanPHP\BeanHelper as RedBeanHelper;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class BeanHelperFactory
{
    protected ModelClassFinder $modelFinder;
    protected ?CacheInterface $cache;
    protected int $ttl;

    public function __construct(ModelClassFinder $modelFinder, ?CacheInterface $cache = null, int $ttl = 86400)
    {
        $this->modelFinder = $modelFinder;
        $this->cache = $cache;
        $this->ttl = $ttl;
    }

    public function create(string $directoryToSearchIn): RedBeanHelper
    {
        if ($this->cache !== null) {
            $redbeanModelClassPaths = $this->cache->get(
                'redbean_model_class_paths',
                function (ItemInterface $item) use ($directoryToSearchIn) {
                    $item->expiresAfter($this->ttl);
                    return $this->modelFinder->findInDirectory($directoryToSearchIn);
                }
            );
        } else {
            $redbeanModelClassPaths = $this->modelFinder->findInDirectory($directoryToSearchIn);
        }

        return new BeanHelper($redbeanModelClassPaths);
    }
}
