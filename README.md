# ISPager
[![Software License](https://img.shields.io/badge/license-MITbrightgreen.svg?style=flat-square)](LICENSE.md)
Class facilitates multiple requests to VK API
## Install
Via Composer
``` bash
$ composer require dantrash/VKRequestClass
```
## Usage
``` php
$request_test = new VKRequest(
    'USER_TOKEN',
    'wall.get'
);
$request_test -> setOptions(['count' => 100,]);
$request_test->vkPrint($request_test -> vkGet());
```
``` php
$request_test = new VKRequest(
    'USER_TOKEN',
    'wall.get'
);
$request_test -> setOptions(['count' => 100,]);
$request_test->vkPrint($request_test -> vkManyGet(3));
```
## License
The MIT License (MIT). Please see [License File](https://github.com/dnoegel/
php-xdg-base-dir/blob/master/LICENSE) for more information.