<?php

/**
 * This file is part of workerman.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the MIT-LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @author walkor<walkor@workerman.net>
 * @copyright walkor<walkor@workerman.net>
 * @link http://www.workerman.net/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

/**
 * 用于检测业务代码死循环或者长时间阻塞等问题
 * 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
 * 然后观察一段时间workerman.log看是否有process_timeout异常
 */

//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
require __DIR__ . '/myfunctions.php';

/**
 * 主逻辑
 * 主要是处理 onConnect onMessage onClose 三个方法
 * onConnect 和 onClose 如果不需要可以不用实现并删除
 */
class Events
{

    private static $socketMaps = [];

    private static function msg($msg)
    {
        echo $msg, date(" e Y-m-d H:i:s \n");
    }

    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     *
     * @param int $client_id 连接id
     */
    static function onConnect($client_id)
    {
        // 向当前client_id发送数据 
        Gateway::sendToClient($client_id, json_encode(['client_id' => $client_id]));
        // 向所有人发送
        //        Gateway::sendToAll("$client_id login\r\n");
    }

    /**
     * 当客户端发来消息时触发
     * @param int $client_id 连接id
     * @param mixed $message 具体消息
     */
    static function onMessage($client_id, $message)
    {
        self::msg($client_id . ',' . $message);
        $rs     = json_decode($message,true);
        if($rs['from_type'] ?? 0){
            $method = 'from_' . $rs['from_type'];
            self::$method($client_id, $rs);
        }
        if($rs['do_type'] ?? 0){
            $method = 'do_' . $rs['do_type'];
            self::$method($rs);
        }
    }

    private static function from_server($client_id, $rs)
    {
        if (empty(self::$socketMaps)) {
            self::msg("No Websocket Map !");
            return 0;
        }
        foreach (self::$socketMaps as $cl => $ip) {
            if ($rs['eip'] == $ip) {
                self::msg("send To eip:{$ip}");
                Gateway::sendToClient($cl, json_encode($rs));
            }
        }
    }

    private static function from_equipment($client_id, $rs)
    {
        self::$socketMaps[$client_id] = $rs['eip'];
    }

    private static function do_http($rs)
    {
        self::msg("do_http");
        if(function_exists("{$rs['action']}")){
            self::msg("action: {$rs['action']}; data: {$rs['data']['Ip']}");
            call_user_func("{$rs['action']}",$rs['data']);
        }
    }

    /**
     * 当用户断开连接时触发
     * @param int $client_id 连接id
     */
    static function onClose($client_id)
    {
        unset(self::$socketMaps[$client_id]);
        // 向所有人发送
        //        GateWay::sendToAll("$client_id logout\r\n");
    }

    static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        // Gateway::sendToAll('An error from_type: ' . $name);
    }
}
