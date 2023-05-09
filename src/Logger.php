<?php

declare(strict_types=1);

namespace Proxity\Logger;

use Proxity\Logger\Handler\HandlerInterface;
use Psr\Log\LoggerInterface;
use Stringable;

final class Logger implements LoggerInterface
{
    /**
     * @var HandlerInterface[]
     */
    private array $handlers;

    private ?Level $level;

    /**
     * @param string $name
     * @param HandlerInterface[] $handlers
     * @param null|Level $level
     */
    public function __construct(
        public readonly string $name,
        array $handlers = [],
        ?Level $level = null
    ) {
        $this->level = $level;
        $this->setHandlers($handlers);
    }

    /**
     * @param HandlerInterface $handler
     * @return void
     */
    public function addHandler(HandlerInterface $handler): void
    {
        $this->handlers[] = $handler;
    }

    /**
     * @return HandlerInterface[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * @param HandlerInterface[] $handlers
     * @return $this
     */
    public function setHandlers(array $handlers): self
    {
        $this->handlers = [];
        foreach ($handlers as $handler) {
            $this->addHandler($handler);
        }

        return $this;
    }

    /**
     * @param Level|null $level
     * @return void
     */
    public function setLevel(?Level $level): void
    {
        $this->level = $level;
    }

    /**
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function emergency(\Stringable|string $message, array $context = []): void
    {
        $this->addRecord(Level::Critical, $message, $context);
    }

    /**
     * @param Level $level
     * @param string|Stringable $message
     * @param array<mixed> $context
     * @param \DateTime $datetime
     * @return void
     */
    public function addRecord(
        Level $level,
        string|Stringable $message,
        array $context = [],
        \DateTime $datetime = new \DateTime()
    ): void {
        $record = new LogRecord(
            $datetime,
            $this->name,
            $level,
            (string)$message,
            /** @phpstan-ignore-next-line */
            getmypid(),
            $context,
        );

        if (!$this->canHandle($level)) {
            return;
        }

        foreach ($this->handlers as $handler) {
            if (!$handler->canHandle($record)) {
                continue;
            }
            $handler->handle($record);
        }
    }

    /**
     * @param Level $level
     * @return bool
     */
    public function canHandle(Level $level): bool
    {
        /** @phpstan-ignore-next-line */
        return is_null($this->level) ?? $level === $this->level;
    }

    /**
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function alert(\Stringable|string $message, array $context = []): void
    {
        $this->addRecord(Level::Alert, $message, $context);
    }

    /**
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function critical(\Stringable|string $message, array $context = []): void
    {
        $this->addRecord(Level::Critical, $message, $context);
    }

    /**
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function error(\Stringable|string $message, array $context = []): void
    {
        $this->addRecord(Level::Error, $message, $context);
    }

    /**
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function warning(\Stringable|string $message, array $context = []): void
    {
        $this->addRecord(Level::Warning, $message, $context);
    }

    /**
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function notice(\Stringable|string $message, array $context = []): void
    {
        $this->addRecord(Level::Notice, $message, $context);
    }

    /**
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function info(\Stringable|string $message, array $context = []): void
    {
        $this->addRecord(Level::Info, $message, $context);
    }

    /**
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function debug(\Stringable|string $message, array $context = []): void
    {
        $this->addRecord(Level::Debug, $message, $context);
    }

    /**
     * @param $level
     * @param \Stringable|string $message
     * @param array<mixed> $context
     * @return void
     */
    public function log($level, \Stringable|string $message, array $context = []): void
    {
        $this->addRecord($this->translateLevel($level), (string)$message, $context);
    }

    /**
     * @param mixed $level
     * @return Level
     */
    public function translateLevel($level): Level
    {
        if ($level instanceof Level) {
            return $level;
        }
        if (!is_int($level)) {
            throw new \InvalidArgumentException("Only integers can be translated to a error level ");
        }

        if (isset(Level::RFC_5424_LEVELS[$level])) {
            return Level::RFC_5424_LEVELS[$level];
        }

        $level = Level::tryFrom($level);

        if (is_null($level)) {
            throw new \InvalidArgumentException('Level "' . $level . '" is not defined, use one of: ' . implode(
                    ', ',
                    Level::VALUES
                ));
        }

        return $level;
    }
}
