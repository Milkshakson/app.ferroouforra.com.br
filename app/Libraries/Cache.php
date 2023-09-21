<?php
namespace App\Libraries;

use CodeIgniter\I18n\Time;
use stdClass;

class Cache
{
    private $cacheSessionKey = 'myCacheTrait';

    private function isFull()
    {
        $cache = $this->getCache();
        return count($cache) >= 10;
    }

    private function removeFirst()
    {
        $cache = $this->getCache();
        if (count($cache) > 0) {
            $itemRemove = $cache[0];
            $keyRemove = false;
            foreach ($cache as $key => $cacheItem) {
                if ($cacheItem->updatedAt->isBefore($itemRemove)) {
                    $keyRemove = $key;
                }

            }
            if ($keyRemove) {
                unset($cache["$keyRemove"]);
                session($this->cacheSessionKey)->set($cache);
            }
        }
        unset($cache);
    }
    public function get(string $key, $diffMinutes = 3)
    {
        try {
            $cache = $this->getCache();
            $cacheItem = $cache["$key"];
            $agora = new Time();
            $diferenca = $agora->getTimestamp() - $cacheItem->updatedAt->getTimestamp();
            $cacheItem->isValid = $diffMinutes <= $diferenca;
        } catch (\Throwable $th) {
            $cacheItem = new stdClass();
            $cacheItem->updatedAt = null;
            $cacheItem->content = null;
            $cacheItem->isValid = false;
            $cacheItem->error = $th->getMessage();
            return $cacheItem;
        }

    }
    private function getCache()
    {
        $cache = session($this->cacheSessionKey);
        if (!is_array($cache)) {
            $cache = [];
        }

        return $cache;
    }
    public function set(string $chave, $valor)
    {
        try {
            if ($this->isFull()) {
                $this->removeFirst();
            }
            $cache = $this->getCache();
            $cacheItem = new stdClass();
            $cacheItem->updatedAt = Time('Y-m-d H:i:s');
            $cacheItem->content = $valor;
            $cache["$chave"] = $cacheItem;
            session($this->cacheSessionKey)->set($cache);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
