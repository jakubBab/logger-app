<?php

declare(strict_types=1);

namespace Proxity\Logger\Handler;

use http\Exception\RuntimeException;
use Proxity\Logger\Formatter\ConsoleFormatter;
use Proxity\Logger\Formatter\FormatterInterface;
use Proxity\Logger\Level;
use Proxity\Logger\LogRecord;

class ConsoleHandler extends AbstractHandler implements HandlerInterface
{
    public function __construct(
        Level $level = Level::Debug,
        public readonly FormatterInterface $formatter = new ConsoleFormatter()
    ) {
        parent::__construct($level);
    }

    /**
     * @inheritDoc
     */
    public function handle(LogRecord $logRecord): void
    {
        $logRecord = $this->formatter->format($logRecord);
        $stream = fopen('php://stderr', 'w');

        if (false === $stream) {
            //not much we can do about it
            throw new RuntimeException("Process was not able to connect to the stderr stream");
        }

        fputs($stream, $logRecord->format);
        fclose($stream);

        $this->logRecord = $logRecord;
    }
}
