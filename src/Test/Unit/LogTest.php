<?php

declare(strict_types=1);

namespace Proxity\Logger\Test\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Proxity\Logger\Handler\ConsoleHandler;
use Proxity\Logger\Level;
use Proxity\Logger\Logger;
use Proxity\Logger\LogRecord;

class LogTest extends TestCase
{
    public function testLoggerWillLogToConsole()
    {
        $logger = new Logger('btc');
        $logger->addHandler(new ConsoleHandler());

        $message = 'this is warning message';

        $logger->warning($message, ['lorem ipsum']);

        $handlers = $logger->getHandlers();
        /** @var  $handledRecord LogRecord */
        $handledRecord = $handlers[0]->getLoggedRecord();

        $this->assertNotNull($handledRecord->format);
        $this->assertStringContainsString($message, $handledRecord->format, 'Logger should have logged the payload');
    }

    public function testLoggerWillHandleOnlyTopLevelDefinedAtRunTime(Level $level = Level::Notice)
    {

        $logger = new Logger('btc', [new ConsoleHandler()], $level);

        $message = 'this is a warning message with btc';
        $logger->warning($message, ['lorem ipsum']);

        $handlers = $logger->getHandlers();
        /** @var  $handledRecord LogRecord */
        $handledRecord = $handlers[0]->getLoggedRecord();

        $this->assertNull(
            $handledRecord,
            sprintf(
                'Handler debug level is %s and global logger level is %s. Output should not printed',
                $handlers[0]->level->getName(),
                $level->getName()
            )
        );
    }

    public function testLoggerWillLogMinimumLevelPerTarget()
    {

        $handler1 = new ConsoleHandler(Level::Debug);
        $handler2 = new ConsoleHandler(Level::Error);
        $handler3 = new ConsoleHandler(Level::Info);

        $logger = new Logger('test', [$handler1, $handler2]);
        $logger->addHandler($handler3);

        $message = 'test message';
        $logger->notice($message, []);

        $processed = [];
        foreach ($logger->getHandlers() as $handler) {
            if (!is_null($handler->getLoggedRecord())) {
                continue;
            }
            $processed[] = $handler;
        }

        $this->assertCount(1, $processed);
        $this->assertTrue($processed[0]->level === Level::Error);
    }

    public function testTranslateLevel()
    {

        $logger = new Logger('not relevant', [], null);

        // Test integer levels
        $this->assertEquals(Level::Info, $logger->translateLevel(Level::Info));
        $this->assertEquals(Level::Error, $logger->translateLevel(Level::Error));
        $this->expectException(\InvalidArgumentException::class);
        $logger->translateLevel(10); // Invalid level

        // Test string levels
        $this->assertEquals(Level::Debug, $logger->translateLevel(Level::Debug));
        $this->assertEquals(Level::Warning, $logger->translateLevel(Level::Warning));
        $this->expectException(InvalidArgumentException::class);
        $logger->translateLevel('invalid'); // Invalid level


        $this->assertEquals(Level::Info, $logger->translateLevel(7));
        $this->expectException(\InvalidArgumentException::class);
        $logger->translateLevel(123213123); // Invalid level
    }
}
