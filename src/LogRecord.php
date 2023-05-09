<?php

declare(strict_types=1);

namespace Proxity\Logger;

class LogRecord
{
    public function __construct(
        public readonly \DateTime $datetime,
        public readonly string $channel,
        public readonly Level $level,
        public readonly string $message,
        public readonly int $pid,
        /** @var array<mixed> */
        public readonly array $context = [],
        public readonly mixed $format = null
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(): array
    {
        return [
            'pid' => $this->pid,
            'channel' => $this->channel,
            'message' => $this->message,
            'context' => $this->context,
            'level' => $this->level->value,
            'level_name' => $this->level->getName(),
            'datetime' => $this->datetime->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param mixed $format
     * @return self
     */
    public function withFormat($format): self
    {
        return new self(
            $this->datetime,
            $this->channel,
            $this->level,
            $this->message,
            $this->pid,
            $this->context,
            $format
        );
    }
}
