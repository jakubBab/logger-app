<?php

declare(strict_types=1);

namespace Proxity\Logger\Test\Unit\Formatter;

use PHPUnit\Framework\TestCase;
use Proxity\Logger\Formatter\LineFormatter;
use Proxity\Logger\Level;
use Proxity\Logger\LogRecord;

final class LineFormatterTest extends TestCase
{
    public function testFormat(): void
    {

        $dateTime = new \DateTime();
        $checkDateTime = clone $dateTime;

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

        $lineNormalizer = new LineFormatter();
        $logRecord = $lineNormalizer->format($logRecord);

        $this->assertNotNull($logRecord->format, 'Normalized value have not been set');
        $this->assertEquals(
            sprintf(
                '[%s][%s] %s.%s: %s %s' . "\n",
                $pid,
                $checkDateTime->format('Y-m-d H:i:s'),
                $channel,
                $level->getName(),
                $message,
                json_encode($context, \JSON_UNESCAPED_SLASHES)
            ),
            $logRecord->format
        );
    }
}
