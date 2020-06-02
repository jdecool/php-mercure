<?php

declare(strict_types=1);

namespace JDecool\Mercure;

final class Update
{
    private $topics;
    private $data;
    private $private;
    private $id;
    private $type;
    private $retry;

    /**
     * @param array|string $topics
     */
    public function __construct($topics, string $data = '', bool $private = false, string $id = null, string $type = null, int $retry = null)
    {
        if (!\is_array($topics) && !\is_string($topics)) {
            throw new \InvalidArgumentException('$topics must be an array of strings or a string');
        }

        $this->topics = (array) $topics;
        $this->data = $data;
        $this->private = $private;
        $this->id = $id;
        $this->type = $type;
        $this->retry = $retry;
    }

    public function getTopics(): array
    {
        return $this->topics;
    }

    public function getData(): string
    {
        return $this->data;
    }

    public function isPrivate(): bool
    {
        return $this->private;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getRetry(): ?int
    {
        return $this->retry;
    }
}
