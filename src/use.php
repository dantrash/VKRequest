<?php
namespace VKRequestClass;

require_once 'VKRequest.php';

define('USER_TOKEN', 'vk_token_string');

$request_test = new VKRequest(
    USER_TOKEN,
    'wall.get'
);
$request_test -> setOptions(['count' => 5,]);
$request_test->vkPrint($request_test -> vkManyGet(3));