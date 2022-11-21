<?php

declare(strict_types=1);

namespace AkbarHossain\CommissionTask\Service;

use Psr\Container\ContainerInterface;

class Container implements ContainerContract
{
    protected array $resolved = [];

    protected array $definitions;

    public function __construct(array $definitions = [])
    {
        $definitions = array_merge(
            $definitions,
            [
                ContainerContract::class => $this,
                ContainerInterface::class => $this,
            ]
        );

        foreach ($definitions as $id => $resolver) {
            $this->set($id, $resolver);
        }
    }

    public function set(string $id, mixed $resolver): static
    {
        $this->definitions[$id] = $resolver;

        return $this;
    }

    public function get(string $id)
    {
        if (!$this->has($id)) {
            throw new \InvalidArgumentException(sprintf("Invalid option '%s'", $id));
        }

        return $this->resolved[$id] ??= $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->definitions) || array_key_exists($id, $this->resolved);
    }

    protected function resolve(string $id): mixed
    {
        return ($value = $this->definitions[$id]) instanceof \Closure ? $value($this) : $value;
    }
}
