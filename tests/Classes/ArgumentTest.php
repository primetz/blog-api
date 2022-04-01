<?php

namespace Tests\Classes;

use App\Classes\Argument;
use PHPUnit\Framework\TestCase;
use function Sodium\add;

class ArgumentTest extends TestCase
{
    private ?Argument $argument;

    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
        Argument $argument = null,
    )
    {
        $this->argument = $argument ?? new Argument();

        parent::__construct($name, $data, $dataName);
    }

    public function argumentProvider(): iterable
    {
        return [
            ['Stanislav', 'Stanislav'],
            [123, '123'],
        ];
    }

    /**
     * @dataProvider argumentProvider
     */
    public function testItReturnsArgumentsValueByName(
        string|int $inputValue,
        string|int $expectedValue
    ): void
    {
        $this->argument->add($expectedValue, $inputValue);

        $this->assertEquals($expectedValue, $this->argument->get($expectedValue));
    }

    /**
     * @dataProvider argumentProvider
     */
    public function testItReturnsValueAsString(
        string|int $inputValue,
        string|int $expectedValue
    ): void
    {
        $this->argument->add($expectedValue, $inputValue);

        $this->assertSame($expectedValue, $this->argument->get($expectedValue));
    }

    /**
     * @dataProvider argumentProvider
     */
    public function testInReturnsNullOnNonExistentKey(
        string|int $inputValue,
        string|int $expectedValue
    ): void
    {
        $this->argument->add($expectedValue, $inputValue);

        $this->assertNull($this->argument->get('not-exist'));
    }

    /**
     * @dataProvider argumentProvider
     */
    public function testItReturnsCollectionArguments(
        string|int $inputValue,
        string|int $expectedValue
    ): void
    {
        $collection = [];

        $collection[$expectedValue] = $inputValue;

        $this->argument->add($expectedValue, $inputValue);

        $this->assertEquals($collection, $this->argument->getArguments());
    }

    /**
     * @dataProvider argumentProvider
     */
    public function testItReturnsAnArray(
        string|int $inputValue,
        string|int $expectedValue
    ): void
    {
        $this->argument->add($expectedValue, $inputValue);

        $this->assertIsArray($this->argument->getArguments());
    }
}
