<?php

namespace App\Services;

use App\Classes\Argument;
use App\Classes\ArgumentInterface;
use App\Exceptions\ArgumentException;
use App\Exceptions\CommandException;
use JetBrains\PhpStorm\Pure;

class ArgumentParserService implements ArgumentParserServiceInterface
{
    private ArgumentInterface $argument;

    #[Pure] public function __construct(ArgumentInterface $argument = null)
    {
        $this->argument = $argument ?? new Argument();
    }

    /**
     * @throws ArgumentException
     * @throws CommandException
     */
    public function parseRawInput(
        array $rawInput,
        array $scheme,
    ): Argument
    {
        foreach ($rawInput as $argument) {
            $arguments = explode('=', $argument);

            if (count($arguments) !== 2) {
                throw new ArgumentException(
                    'Количество аргументов не равно 2.'
                );
            }

            if (!empty($arguments[0]) && !empty($arguments[1])) {
                $this->argument->add($arguments[0], $arguments[1]);
            }
        }

        foreach ($scheme as $argument) {
            if (!array_key_exists($argument, $this->argument->getArguments())) {
                throw new CommandException(
                    sprintf('No required argument provided %s', $argument)
                );
            }

            if (empty($this->argument->getArguments()[$argument])) {
                throw new CommandException(
                    sprintf('Empty argument provided: %s', $argument)
                );
            }
        }

        return $this->argument;
    }
}
