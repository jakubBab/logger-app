<?php

declare(strict_types=1);

namespace Proxity\Logger\Formatter;

use Proxity\Logger\LogRecord;

final class LineFormatter extends AbstractNormalizer implements FormatterInterface
{
    public const FORMAT = "[%pid%][%datetime%] %channel%.%level_name%: %message% %context%\n";

    /**
     * @inheritDoc
     */
    public function format(LogRecord $logRecord): LogRecord
    {
        $format = self::FORMAT;
        $format = str_replace('%pid%', (string)$logRecord->pid, $format);
        $format = str_replace('%datetime%', $logRecord->datetime->format(self::DEFAULT_DATE_TIME_FORMATTER), $format);
        $format = str_replace('%channel%', $logRecord->channel, $format);
        $format = str_replace('%level_name%', $logRecord->level->getName(), $format);
        $format = str_replace('%message%', $logRecord->message, $format);

        $format = str_replace('%context%', $this->normalizeContext($logRecord->context), $format);

        return $logRecord->withFormat($format);
    }
}
