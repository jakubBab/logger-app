<?php

namespace Proxity\Logger\Handler;

use Proxity\Logger\LogRecord;

interface HandlerInterface
{
    /**
     * @param LogRecord $logRecord
     * @return void
     */
    public function handle(LogRecord $logRecord): void;

    /**
     * @param LogRecord $logRecord
     * @return bool
     */
    public function canHandle(LogRecord $logRecord): bool;
}
