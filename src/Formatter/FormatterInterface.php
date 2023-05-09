<?php

namespace Proxity\Logger\Formatter;

use Proxity\Logger\LogRecord;

interface FormatterInterface
{
    /**
     * @param LogRecord $logRecord
     * @return LogRecord
     */
    public function format(LogRecord $logRecord): LogRecord;
}
