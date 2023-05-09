<?php

declare(strict_types=1);

namespace Proxity\Logger\Formatter;

use Proxity\Logger\LogRecord;

class ConsoleFormatter extends AbstractNormalizer implements FormatterInterface
{
    /**
     * @inheritDoc
     */
    public function format(LogRecord $logRecord): LogRecord
    {
        $context = $this->normalizeContext($logRecord->context);
        $record = $logRecord->toArray();
        $record['context'] = $context;
        $formatted = implode("\t", $record) . PHP_EOL;
        return $logRecord->withFormat($formatted);
    }
}
