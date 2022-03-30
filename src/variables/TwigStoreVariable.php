<?php
declare(strict_types=1);

namespace juni\themehelper\variables;

final class TwigStoreVariable
{
    // Private Properties
    // =========================================================================

    private array $store = [];

    // Public Methods
    // =========================================================================

    /**
     * Store the value with the key in the store array for the duration of the request.
     *
     * @param string $key
     * @param mixed $value
     */
    public function store(string $key, $value): void
    {
        $this->store[$key] = $value;
    }

    /**
     * Retrieve the value, based on the key and return `null` if the key doesn't exist.
     *
     * @param string $key
     * @return mixed
     */
    public function retrieve(string $key)
    {
        return $this->store[$key] ?? null;
    }

    /**
     * Check whether a key has been stored.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->store);
    }
}
