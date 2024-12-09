<?php

namespace App;

class View
{
    private string $name;

    private array $args;

    public function __construct(string $name, array $args = [])
    {
        $this->name = $name;
        $this->args = $args;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getArgs(): array
    {
        return $this->args;
    }

    static function render(string $name, array $args = []): static
    {
        return new static($name, $args);
    }
}
