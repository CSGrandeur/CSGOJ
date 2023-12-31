<?php
// Author: CSGrandeur

namespace think\sae;

use think\Exception;

/**
 * SAE Memcache缓存驱动
 * @author    liu21st <liu21st@gmail.com>
 */
class Cache
{
    protected $handler = null;
    protected $options = [
        'host'       => '127.0.0.1',
        'port'       => 11211,
        'expire'     => 0,
        'timeout'    => false,
        'persistent' => false,
        'prefix'     => '',
    ];

    /**
     * 架构函数
     * @param array $options 缓存参数
     * @access public
     */
    public function __construct($options = [])
    {
        if (!function_exists('sae_debug')) {
            throw new \BadFunctionCallException('must run at sae');
        }
        $this->handler = new \Memcached();
        if (!$this->handler) {
            throw new Exception('memcache init error');
        }
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
    }

    /**
     * 读取缓存
     * @access public
     * @param string $name 缓存变量名
     * @return mixed
     */
    public function get($name)
    {
        return $this->handler->get($_SERVER['HTTP_APPVERSION'] . '/' . $this->options['prefix'] . $name);
    }

    /**
     * 写入缓存
     * @access public
     * @param string    $name 缓存变量名
     * @param mixed     $value  存储数据
     * @param integer   $expire  有效时间（秒）
     * @return bool
     */
    public function set($name, $value, $expire = null)
    {
        if (is_null($expire)) {
            $expire = $this->options['expire'];
        }
        $name = $this->options['prefix'] . $name;
        if ($this->handler->set($_SERVER['HTTP_APPVERSION'] . '/' . $name, $value, $expire)) {
            return true;
        }
        return false;
    }

    /**
     * 删除缓存
     * @param    string  $name 缓存变量名
     * @param bool|false $ttl
     * @return bool
     */
    public function rm($name, $ttl = false)
    {
        $name = $_SERVER['HTTP_APPVERSION'] . '/' . $this->options['prefix'] . $name;
        return false === $ttl ?
        $this->handler->delete($name) :
        $this->handler->delete($name, $ttl);
    }

    /**
     * 清除缓存
     * @access public
     * @return bool
     */
    public function clear()
    {
        return $this->handler->flush();
    }

    /**
     * 获得SaeKv对象
     */
    private function getKv()
    {
        static $kv;
        if (!$kv) {
            $kv = new \SaeKV();
            if (!$kv->init()) {
                throw new Exception('KVDB init error');
            }
        }
        return $kv;
    }

}
