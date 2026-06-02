<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Reflector;
use InvalidArgumentException;
use ReflectionMethod;
use ReflectionParameter;

abstract class Controller extends BaseController
{
    /**
     * Laravel passes route parameters with array_values(), so nested routes like
     * kepala-cabor/{cabor}/atlet/{atlet} inject Cabor into the first type-hinted
     * argument when the controller method omits Cabor. Match by name and type instead.
     *
     * @param  array<string|int, mixed>  $parameters
     */
    public function callAction($method, $parameters)
    {
        return $this->{$method}(...$this->resolveActionArguments($method, $parameters));
    }

    /**
     * @param  array<string|int, mixed>  $parameters
     * @return list<mixed>
     */
    protected function resolveActionArguments(string $method, array $parameters): array
    {
        $arguments = [];
        $remaining = $parameters;

        foreach ((new ReflectionMethod($this, $method))->getParameters() as $parameter) {
            $arguments[] = $this->resolveActionArgument($parameter, $remaining);
        }

        return $arguments;
    }

    /**
     * @param  array<string|int, mixed>  $remaining
     */
    protected function resolveActionArgument(ReflectionParameter $parameter, array &$remaining): mixed
    {
        $name = $parameter->getName();

        if (array_key_exists($name, $remaining)) {
            return $this->pullArgument($remaining, $name);
        }

        $className = Reflector::getParameterClassName($parameter);

        if ($className) {
            foreach ($remaining as $key => $value) {
                if ($value instanceof $className) {
                    return $this->pullArgument($remaining, $key);
                }
            }
        }

        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        if ($parameter->allowsNull()) {
            return null;
        }

        throw new InvalidArgumentException(sprintf(
            'Unable to resolve dependency [%s] in class [%s]',
            $name,
            static::class
        ));
    }

    /**
     * @param  array<string|int, mixed>  $remaining
     */
    protected function pullArgument(array &$remaining, string|int $key): mixed
    {
        $value = $remaining[$key];
        unset($remaining[$key]);

        return $value;
    }
}
