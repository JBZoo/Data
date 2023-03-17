<?php

/**
 * JBZoo Toolbox - Data.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/Data
 */

declare(strict_types=1);

namespace JBZoo\Data;

use function JBZoo\Utils\bool;
use function JBZoo\Utils\float;
use function JBZoo\Utils\int;

/**
 * @phan-file-suppress PhanUndeclaredMethod
 */
trait AliasesTrait
{
    public function getInt(string $key, int $default = 0): int
    {
        return int($this->get($key, $default));
    }

    public function getIntNull(string $key, ?int $default = null): ?int
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->getInt($key, $default ?? 0);
    }

    public function getFloat(string $key, float $default = 0.0): float
    {
        return float($this->get($key, $default));
    }

    public function getFloatNull(string $key, ?float $default = null): ?float
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->getFloat($key, $default ?? 0.0);
    }

    public function getString(string $key, string $default = ''): string
    {
        return (string)$this->get($key, $default);
    }

    public function getStringNull(string $key, ?string $default = null): ?string
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->getString($key, $default ?? '');
    }

    public function getArray(string $key, array $default = []): array
    {
        return (array)$this->get($key, $default);
    }

    public function getArrayNull(string $key, ?array $default = null): ?array
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->getArray($key, $default ?? []);
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getBool(string $key, bool $default = false): bool
    {
        return bool($this->get($key, $default));
    }

    /**
     * @SuppressWarnings(PHPMD.BooleanGetMethodName)
     */
    public function getBoolNull(string $key, ?bool $default = null): ?bool
    {
        if (!$this->has($key)) {
            return $default;
        }

        return $this->getBool($key, $default ?? false);
    }

    /**
     * @psalm-suppress UnsafeInstantiation
     * @psalm-suppress ImplicitToStringCast
     */
    public function getSelf(string $key, array|self $default = []): self
    {
        if ($this->has($key) && $this->get($key) !== null) {
            // @phpstan-ignore-next-line
            return new static((array)$this->get($key, $default));
        }

        // @phpstan-ignore-next-line
        return new static($default);
    }

    /**
     * @psalm-suppress UnsafeInstantiation
     * @psalm-suppress ImplicitToStringCast
     */
    public function getSelfNull(string $key, array|self $default = null): ?self
    {
        if (!$this->has($key)) {
            // @phpstan-ignore-next-line
            return $default !== null ? new static($default) : null;
        }

        return $this->getSelf($key, $default ?? []);
    }

    public function findInt(string $key, int $default = 0): int
    {
        return int($this->find($key, $default));
    }

    public function findFloat(string $key, float $default = 0.0): float
    {
        return float($this->find($key, $default));
    }

    public function findString(string $key, string $default = ''): string
    {
        return (string)$this->find($key, $default);
    }

    public function findArray(string $key, array $default = []): array
    {
        return (array)$this->find($key, $default);
    }

    public function findBool(string $key, bool $default = false): bool
    {
        return bool($this->find($key, $default));
    }

    /**
     * @psalm-suppress UnsafeInstantiation
     */
    public function findSelf(string $key, array $default = []): self
    {
        if ($this->has($key) && $this->get($key) !== null) {
            // @phpstan-ignore-next-line
            return new static((array)$this->find($key, $default));
        }

        // @phpstan-ignore-next-line
        return new static($default);
    }
}
