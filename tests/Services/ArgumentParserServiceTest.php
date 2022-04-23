<?php

namespace Tests\Services;

use App\Classes\Argument;
use App\Classes\ArgumentInterface;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Services\ArgumentParserService;
use App\Services\ArgumentParserServiceInterface;
use PHPUnit\Framework\TestCase;

class ArgumentParserServiceTest extends TestCase
{
    private ?ArgumentParserService $argumentParserService;

    public function __construct(
        ?string $name = null,
        array $data = [],
        $dataName = '',
        ArgumentParserService $argumentParserService = null,
    )
    {
        $this->argumentParserService = $argumentParserService ?? new ArgumentParserService();

        parent::__construct($name, $data, $dataName);
    }

    private function getArgumentStub(): ArgumentInterface
    {
        return new class implements ArgumentInterface {

            private array $arguments = [];

            public function add(string $key, string $value): void
            {
                $this->arguments[$key] = $value;
            }

            public function get(string $argument): ?string
            {
                return $this->arguments[$argument] ?? null;
            }

            public function getArguments(): array
            {
                return $this->arguments;
            }
        };
    }

    public function argumentStubProvider(): iterable
    {
        return [
            [new ArgumentParserService(), Argument::class],
            [new ArgumentParserService($this->getArgumentStub()), $this->getArgumentStub()::class]
        ];
    }

    public function argumentProvider(): iterable
    {
        return [
            ['Stanislav', 'Stanislav', 'Stanislav'],
            [123, '123', '123'],
        ];
    }

    /**
     * @dataProvider argumentStubProvider
     */
    public function testItImplementsDependencyInversionForArgumentInterface(
        string|ArgumentParserServiceInterface $argumentParserService,
        string|ArgumentInterface $expectedValue

    ): void
    {

        $this->assertInstanceOf(
            $expectedValue,
            $argumentParserService->parseRawInput(
                ['name=value'], ['name']
            )
        );
    }

    /**
     * @dataProvider argumentProvider
     * @throws ArgumentException
     * @throws CommandException
     */
    public function testItThrowsAnExceptionWhenArgumentIsAbsent(
        string|int $inputValue,
        string|int $expectedValue,
        string|int $schemeValue,
    ): void
    {
        $this->expectException(ArgumentException::class);

        $this->expectExceptionMessage('Parameters must be in the format fieldName=fieldValue');

        $this->argumentParserService->parseRawInput([$inputValue], [$schemeValue]);
    }

    /**
     * @dataProvider argumentProvider
     * @throws ArgumentException
     * @throws CommandException
     */
    public function testItThrowsAnExceptionWhenNoRequiredArgumentProvided(
        string|int $inputValue,
        string|int $expectedValue,
    ): void
    {
        $this->expectException(CommandException::class);

        $this->expectExceptionMessage('No required argument provided: RequiredValue');

        $this->argumentParserService->parseRawInput(
            [sprintf('%s=%s', $inputValue, $expectedValue)],
            ['RequiredValue']
        );
    }
}
