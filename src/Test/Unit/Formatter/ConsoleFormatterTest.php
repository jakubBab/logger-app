<?php

declare(strict_types=1);

namespace Proxity\Logger\Test\Unit\Formatter;

use PHPUnit\Framework\TestCase;
use Proxity\Logger\Formatter\ConsoleFormatter;
use Proxity\Logger\Formatter\LineFormatter;
use Proxity\Logger\Level;
use Proxity\Logger\LogRecord;

final class ConsoleFormatterTest extends TestCase
{
    public function testFormat(): void
    {

        $dateTime = new \DateTime();
        $dateTime = $dateTime->setTimestamp(1683553932);


        $channel = 'test';
        $pid = 123;
        $level = Level::Debug;
        $message = 'some message';
        $context = ['process' => 'test case'];

        $logRecord = new LogRecord(
            $dateTime,
            $channel,
            $level,
            $message,
            $pid,
            $context,
        );

        $lineNormalizer = new ConsoleFormatter();
        $logRecord = $lineNormalizer->format($logRecord);

        $this->assertNotNull($logRecord->format, 'Console formatter has not been set');
        $this->assertEquals(
            sprintf(
                '%s' . "\t" . '%s' . "\t" . '%s' . "\t" . '%s' . "\t" . '%s' . "\t" . '%s' . "\t" . '%s',
                $pid,
                $channel,
                $message,
                json_encode($context, \JSON_UNESCAPED_SLASHES),
                $level->value,
                $level->getName(),
                $dateTime->format('Y-m-d H:i:s')
            ) . PHP_EOL,
            $logRecord->format,
            'Wrong console formatter result'
        );
    }
}
