<?php

if (!function_exists('array_any')) {
    /**
     * @param array $array
     * @param callable $fn
     * @return bool
     *  Behave like some() of the JavaScript (https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/some)
     */
    function array_any(array $array, callable $fn): bool
    {
        foreach ($array as $value) {
            if ($fn($value)) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('map')) {
    /**
     * @param array $collection
     * @param callable $callback
     * @return array
     *  Behave like map() of the JavaScript (https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Global_Objects/Array/some)
     */
    function map(array $collection, callable $callback)
    {
        $aggregation = [];

        foreach ($collection as $index => $element) {
            $aggregation[$index] = $callback($element, $index, $collection);
        }

        return $aggregation;
    }
}
