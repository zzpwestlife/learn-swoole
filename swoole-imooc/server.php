<?php

function server()
{
    //创建 Server 对象，监听 127.0.0.1:9501 端口
    $server = new Swoole\Server("127.0.0.1", 9501);

    $server->set(array(
        'reactor_num' => 2, //reactor thread num
        'worker_num' => 8,    //worker process num
        'max_request' => 5000,
    ));

    // 监听连接进入事件
    // $fd 客户端连接的唯一标志
    $server->on('Connect', function ($server, $fd, $reactor_id) {
        echo "Client: Connect.\n" . ', fd: ' . $fd . ' , reactor_id: ' . $reactor_id;
    });

    // 监听数据接收事件
    $server->on('Receive', function ($server, $fd, $reactor_id, $data) {
        $server->send($fd, "Server: " . $data . ', fd: ' . $fd . ' , reactor_id: ' . $reactor_id);
    });

    // 监听连接关闭事件
    $server->on('Close', function ($server, $fd) {
        echo "Client: Close.\n";
    });

    // 启动服务器
    $server->start();
}