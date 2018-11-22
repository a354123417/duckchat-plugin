<?php
/**
 * Created by PhpStorm.
 * User: childeYin<尹少爷>
 * Date: 19/07/2018
 * Time: 4:47 PM
 */

class ZalyRedis
{
    public $redis;

    private $redisKey     = "redis";

    public function __construct()
    {
        $this->redis = new Redis();
        $redisConfig = ZalyConfig::getConfig($this->redisKey);
        $this->redis->connect($redisConfig['host'], $redisConfig['port']);
    }

    public function set($key, $val, $timeOut= 300)
    {
        return $this->redis->set($key, $val, $timeOut);
    }

    public function get($key)
    {
        return $this->redis->get($key);
    }

    public function hset($key, $field, $val)
    {
        return $this->redis->hSet($key, $field, $val);
    }

    public function del($key)
    {
        return $this->redis->del($key);
    }

    public function hget($key, $field)
    {
        return $this->redis->hGet($key, $field);
    }

    public function hdel($key, $field)
    {
        return $this->redis->hDel($key, $field);
    }

}