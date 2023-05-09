<?php

declare(strict_types=1);

namespace Proxity\Logger\Handler;

use Proxity\Logger\Formatter\FormatterInterface;
use Proxity\Logger\Formatter\LineFormatter;
use Proxity\Logger\Level;
use Proxity\Logger\LogRecord;

class FileHandler extends AbstractHandler implements HandlerInterface
{
    public function __construct(
        public readonly string $file,
        Level $level = Level::Debug,
        public readonly FormatterInterface $formatter = new LineFormatter(),
        public readonly bool $withLock = false
    ) {
        parent::__construct($level);
    }

    /**
     * @inheritDoc
     */
    public function handle(LogRecord $logRecord): void
    {
    }
}
