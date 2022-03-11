<?php

namespace App\Decorators;

use App\Classes\Argument;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use App\Services\ArgumentParserService;
use App\Services\ArgumentParserServiceInterface;
use JetBrains\PhpStorm\Pure;

abstract class Decorator implements DecoratorInterface
{
    protected array $arguments = [];

    private ArgumentParserServiceInterface $argumentParserService;

    #[Pure] public function __construct(
        array $arguments,
        ArgumentParserServiceInterface $argumentParserService = null,
    )
    {
        $this->arguments = $arguments;

        $this->argumentParserService = $argumentParserService ?? new ArgumentParserService();
    }

    /**
     * @throws ArgumentException
     * @throws CommandException
     */
    public function getFieldData(): Argument
    {
        return $this->argumentParserService
            ->parseRawInput($this->arguments, $this->getRequiredFields());
    }

    abstract public function getRequiredFields(): array;
}
