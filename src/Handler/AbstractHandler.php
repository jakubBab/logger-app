<?php

declare(strict_types=1);

namespace Proxity\Logger\Handler;

use Proxity\Logger\Level;
use Proxity\Logger\LogRecord;

abstract class AbstractHandler
{
    protected ?LogRecord $logRecord = null;

    public function __construct(public readonly Level $level = Level::Debug)
    {
    }

    /**
     * @param LogRecord $logRecord
     * @return bool
     */
    public function canHandle(LogRecord $logRecord): bool
    {
        return $logRecord->level->value >= $this->level->value;
    }

    /**
     * @return LogRecord|null
     */
    public function getLoggedRecord(): ?LogRecord
    {
        return $this->logRecord;
    }
}
