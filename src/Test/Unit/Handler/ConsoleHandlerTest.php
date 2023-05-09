<?php

declare(strict_types=1);

namespace Proxity\Logger\Test\Unit\Handler;

use PHPUnit\Framework\TestCase;
use Proxity\Logger\Formatter\ConsoleFormatter;
use Proxity\Logger\Handler\ConsoleHandler;
use Proxity\Logger\Level;
use Proxity\Logger\LogRecord;

class ConsoleHandlerTest extends TestCase
{
    public function testHandle()
    {
        $context = ['process' => 'test case'];

        $channel = 'database';
        $message = 'logged in console';
        $pid = 321;

        $logRecord = new LogRecord(
            new \DateTime(),
            $channel,
            Level::Debug,
            $message,
            $pid,
            $context,
        );

        $handler = new ConsoleHandler(Level::Debug, new ConsoleFormatter());

        $handler->handle($logRecord);

        $formatted = $handler->getLoggedRecord()->format;

        $this->assertNotNull($formatted, 'Handler has not processed the record');

        $this->assertStringContainsString($channel, $formatted);
        $this->assertStringContainsString($message, $formatted);
        $this->assertStringContainsString((string)$pid, $formatted);
    }
}
