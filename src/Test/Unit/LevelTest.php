<?php

declare(strict_types=1);

namespace Proxity\Logger\Test\Unit;

use PHPUnit\Framework\TestCase;
use Proxity\Logger\Level;

class LevelTest extends TestCase
{
    public function testGetName()
    {
        $this->assertEquals('DEBUG', Level::Debug->getName());
        $this->assertEquals('INFO', Level::Info->getName());
        $this->assertEquals('WARNING', Level::Warning->getName());
        $this->assertEquals('ERROR', Level::Error->getName());
    }

    public function testRfc5424Levels()
    {
        $this->assertEquals(Level::RFC_5424_LEVELS[7], Level::Debug);
        $this->assertEquals(Level::RFC_5424_LEVELS[6], Level::Info);
        $this->assertEquals(Level::RFC_5424_LEVELS[4], Level::Warning);
        $this->assertEquals(Level::RFC_5424_LEVELS[3], Level::Error);
    }
}
