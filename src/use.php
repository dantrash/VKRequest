<?php
namespace VKRequestClass;

require_once 'VKRequest.php';

define('USER_TOKEN', '62331ec8745ced335f1d3c2a410f6a8975f8ee393cc3a0b923505fdfaa4bfe5446cf61713c491ea19c5f4');

$request_test = new VKRequest(
    '62331ec8745ced335f1d3c2a410f6a8975f8ee393cc3a0b923505fdfaa4bfe5446cf61713c491ea19c5f4',
    'wall.get'
);
$request_test -> setOptions(['count' => 5,]);
$request_test->vkPrint($request_test -> vkManyGet(3));