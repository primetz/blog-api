<?php

namespace Tests\Commands\Create;

use App\Commands\Create\CreateEntityCommand;
use App\Entities\Comment\Comment;
use App\Entities\EntityInterface;
use App\Entities\Post\Post;
use App\Entities\User\User;
use JetBrains\PhpStorm\Pure;
use PHPUnit\Framework\TestCase;

class CreateEntityCommandTest extends TestCase
{
    #[Pure] public function entityProvider(): iterable
    {
        return [
            [
                new User(
                    'Stanislav',
                    'Rodikov',
                    'example@example.com'
                )
            ],
            [
                new Post(
                    1,
                    'Post title',
                    'Post text'
                )
            ],
            [
                new Comment(
                    1,
                    1,
                    'Comment text'
                )
            ],
        ];
    }

    /**
     * @dataProvider entityProvider
     */
    public function testItReturnsEntityPassedToConstructor(
        EntityInterface $inputEntity
    ): void
    {
        $createEntityCommand = new CreateEntityCommand($inputEntity);

        $this->assertEquals(
            spl_object_hash($inputEntity),
            spl_object_hash($createEntityCommand->getEntity())
        );
    }
}
