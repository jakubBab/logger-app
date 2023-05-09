<?php

declare(strict_types=1);

namespace Proxity\Logger\Formatter;

abstract class AbstractNormalizer
{
    public const DEFAULT_DATE_TIME_FORMATTER = 'Y-m-d H:i:s';

    /**
     * @param array<mixed> $context
     * @return string
     */
    protected function normalizeContext(array $context): string
    {
        $normalized = [];
        foreach ($context as $key => $value) {
            if (!$value instanceof \Exception) {
                if (is_array($value)) {
                    $value = json_encode($value, \JSON_UNESCAPED_SLASHES);
                }
                $normalized [$key] = $value;
                continue;
            }
            $normalized [$key] = $this->normalize($value);
        }
        /** @phpstan-ignore-next-line */
        return trim(json_encode($normalized, \JSON_UNESCAPED_SLASHES));
    }

    /**
     * @param \Exception $exception
     * @return array<string,mixed>
     */
    protected function normalize(\Exception $exception): array
    {
        return
            [
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ];
    }
}
