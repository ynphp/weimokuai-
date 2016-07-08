<?php 
class redisDB{
    private $redis; //redis对象
    /**
     * 初始化Redis
     * $config = array(
     *  'server' => '127.0.0.1' 服务器
     *  'port'   => '6379' 端口号
     * )
     * @param array $config
     */
    function __construct($config = array()){
        $this->redis = new Redis();
        $this->redis->connect('localhost','6379');
        return $this->redis;
    }
   
    /**
     * 设置值
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param int $timeOut 时间
     */
    public function set($key, $value, $timeOut = 0,$type='json') {
        if($type=='serialize')
        {
            $value = serialize($value);
        }
        else
        {
            $value = json_encode($value);
        }
           
        $retRes = $this->redis->set($key, $value);
        if ($timeOut > 0) $this->redis->setTimeout($key, $timeOut);
        return $retRes;
    }
   
    /**
     * 通过KEY获取数据
     * @param string $key KEY名称
     */
    public function get($key,$type='json') {
        $result = $this->redis->get($key);
           
        if($type=='serialize')
        {
            return unserialize($result);
        }
        else
        {
            return json_decode($result);
        }
    }
   
    /**
     * 删除一条数据
     * @param string $key KEY名称
     */
    public function delete($key) {
        return $this->redis->delete($key);
    }
   
    /**
     * 清空数据
     */
    public function flushAll() {
        return $this->redis->flushAll();
    }
   
    /**
     * 数据入队列
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function push($key, $value ,$right = true) {
        $value = json_encode($value);
        return $right ? $this->redis->rPush($key, $value) : $this->redis->lPush($key, $value);
    }
     /**
     * 获取列表
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param bool $right 是否从右边开始入
     */
    public function getrange($key, $start=0 ,$end=0) {
		if(!$end)$end = $this->redis->lLen($key);
        return $this->redis->lrange($key,$start,$end);
    }
	/**
     * 获取列表长度
     * @param string $key KEY名称
     */
	 public function getlen($key, $start=0 ,$end=0) {
        return $this->redis->lLen($key);
    }
	
    /**
     * 数据出队列
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     */
    public function pop($key , $left = true) {
        $val = $left ? $this->redis->lPop($key) : $this->redis->rPop($key);
        return json_decode($val);
    }
   
    /**
     * 数据自增
     * @param string $key KEY名称
     */
    public function incr($key) {
        return $this->redis->incr($key);
    }
   
    /**
     * 数据自减
     * @param string $key KEY名称
     */
    public function decrement($key) {
        return $this->redis->decr($key);
    }
   
    /**
     * key是否存在，存在返回ture
     * @param string $key KEY名称
     */
    public function exists($key) {
        return $this->redis->exists($key);
    }
   
    /**
     * 返回redis对象
     * redis有非常多的操作方法，我们只封装了一部分
     * 拿着这个对象就可以直接调用redis自身方法
     */
    public function redis() {
        return $this->redis;
    }
	
	/**
     * 查找KEY是否存在
     * @param string $key KEY名称
     */
	 public function keys($key) {
        return $this->redis->keys($key);
    }
	//
}
/*
include 'redis.php';
$redis =  new redisDB();
$key = 'fields';
$value = '好脚本';   //value可以是字符串或者数组
$redis->set($key,$value);
//获取fields的值也很简单
$fvalue = $redis->get('fields');
print_r($fvalue);
*/
?>