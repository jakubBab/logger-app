<?php

declare(strict_types=1);

namespace Proxity\Logger;

enum Level: int
{
    case Debug = 100;
    case Info = 200;
    case Notice = 250;
    case Warning = 300;
    case Error = 400;
    case Critical = 500;
    case Alert = 550;
    case Emergency = 600;

    public const RFC_5424_LEVELS = [
        7 => Level::Debug,
        6 => Level::Info,
        5 => Level::Notice,
        4 => Level::Warning,
        3 => Level::Error,
        2 => Level::Critical,
        1 => Level::Alert,
        0 => Level::Emergency,
    ];

    public const VALUES = [
        100,
        200,
        250,
        300,
        400,
        500,
        550,
        600,
    ];

    /**
     * @param Level $level
     * @return bool
     */
    public function withinLevel(Level $level): bool
    {
        return $this->value <= $level->value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return match ($this) {
            self::Debug => 'DEBUG',
            self::Info => 'INFO',
            self::Notice => 'NOTICE',
            self::Warning => 'WARNING',
            self::Error => 'ERROR',
            self::Critical => 'CRITICAL',
            self::Alert => 'ALERT',
            self::Emergency => 'EMERGENCY',
        };
    }
}
