<?php


namespace App\Cache;


use Psr\Cache\CacheItemInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class CacheBuilder
{
    public static function build(string $itemName,$dataToSet, int $expiredAfter)
    {
        $cache = new FilesystemAdapter();
        /**
         * @var CacheItemInterface $element
         */
        $element = $cache->getItem($itemName);

        if (!$element->isHit()) {
            $element->set($dataToSet);
            $element->expiresAfter($expiredAfter);
            $cache->save($element);
        }
        return $element->get();
    }
}