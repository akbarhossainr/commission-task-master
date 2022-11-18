<?php

namespace AkbarHossain\CommissionTask\Service;

class Config
{
    protected array $config;

    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->config)) {
            return $this->config[$key];
        }

        return $default;
    }

    public function set(string $key, $value): self
    {
        $this->config[$key] = $value;

        return $this;
    }
}
