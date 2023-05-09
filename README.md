Logger  PSR-3 logger
=====================


Features
--------

* Compatible with PSR-3 logger interface
* Multiple handlers support
* Exception logging support
* Multiple logging levels on application and handler level



Usage
-----

```php
use \Azurre\Component\Logger;
use \Azurre\Component\Logger\Handler\File;

$logger = new Logger('channel',[new ConsoleHandler()]);
$logger->addHandler(new FileHandler());
$logger->debug('Log something for debug');
```



Logger output
```
5393    test    test message    []      250     NOTICE  2023-05-09 15:02:41
```

Extra points
-----
[lock component](https://symfony.com/doc/current/components/lock.html)
Improvements

1. Introduce processors and apply before handlers to re-define given logRecord 
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
Complexity would be O(k) where k is the number of current records.
