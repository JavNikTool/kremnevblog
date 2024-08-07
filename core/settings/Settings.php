<?php

namespace core\settings;
class Settings
{
    private static ?Settings $object;
    private array $properties;

    private function __construct()
    {
    }

    private function __clone(): void
    {
    }

    public static function get(): Settings
    {
        self::$object ??= new self();
        return self::$object;
    }

    public function __get(string $key): ?string
    {
        if (array_key_exists($key, $this->properties)) {
            return $this->properties[$key];
        } else {
            return null;
        }
    }

    public function __set(string $key, mixed $value): void
    {
        $this->properties[$key] = $value;
    }

    public function list(): array
    {
        return $this->properties;
    }
}