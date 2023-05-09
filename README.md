Logger  PSR-3 logger
=====================


Features
--------

* Compatible with PSR-3 logger interface
* Multiple handlers support
* Exception logging support
* Multiple logging levels on application and handler level


Available commands
-----


```php
composer run phpunit
composer run phpstan
composer run style
```


Usage
-----

```php
use Proxity\Logger\Logger;
use Proxity\Logger\Handler\ConsoleHandler;

$logger = new Logger('channel',[new ConsoleHandler()]);
$logger->addHandler(new ConsoleHandler());
$logger->debug('Log something for debug');
```

Logger output
```
5393    test    test message    []      250     NOTICE  2023-05-09 15:02:41
```

Extra points
-----

Improvements

1. The app is not production ready. It does meet criteria given in the task however further work is required. 
2. Introduce processors and apply before handlers to re-define given logRecord 
2. User fibers for heavy processes. However, it will add extra complexity to the app 
3. Add support for locked resources. Add [lock component](https://symfony.com/doc/current/components/lock.html)  or apply wait&try mechanism and on general fail implement fall-back option
4. Improve formatters for clearer output eg. titles etc.
5. Potentially Logger::addRecord function can be optimized. It has O(n) complexity as it has to itterate over all handlers. Possible solution would be to narrow down handlers that are allowed to be used and then apply the logic: eg 

```php
$handlersToUse = [];

foreach ($this->handlers as $handler) {
    if ($handler->canHandle($record)) {
        $handlersToUse[] = $handler;
    }
}

foreach ($handlersToUse as $handler) {
    $handler->handle($record);
}
```
Complexity would be O(k) where k is the number of handlers "eligible" to handle current record
