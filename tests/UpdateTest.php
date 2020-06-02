<?php

declare(strict_types=1);

namespace JDecool\Mercure\Tests;

use JDecool\Mercure\Update;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    /**
     * @dataProvider updateProvider

     * @param mixed $topics
     * @param mixed $data
     */
    public function testCreateUpdate($topics, $data, bool $private = false, string $id = null, string $type = null, int $retry = null): void
    {
        $update = new Update($topics, $data, $private, $id, $type, $retry);
        $this->assertSame((array) $topics, $update->getTopics());
        $this->assertSame($data, $update->getData());
        $this->assertSame($private, $update->isPrivate());
        $this->assertSame($id, $update->getId());
        $this->assertSame($type, $update->getType());
        $this->assertSame($retry, $update->getRetry());
    }

    public function updateProvider(): array
    {
        return [
            ['http://example.com/foo', 'payload', true, 'id', 'type', 1936],
            [['https://mercure.rocks', 'https://github.com/dunglas/mercure'], 'payload'],
        ];
    }

    public function testInvalidTopic(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Update(1, 'data');
    }
}
