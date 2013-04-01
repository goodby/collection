<?php

namespace Goodby\Collection;

use Traversable;

class Collection implements \IteratorAggregate, \Countable
{
    /**
     * @var array
     */
    private $array = [];

    /**
     * @param array $array
     */
    public function __construct(array $array = [])
    {
        $this->array = $array;
    }

    /**
     * Removes the last element from an array and returns that element.
     * @mutator
     * @return mixed
     */
    public function pop()
    {
        return array_pop($this->array);
    }

    /**
     * Adds one or more elements to the end of an array and returns the new length of the array.
     * @mutator
     * @param mixed $element,...
     * @return int
     */
    public function push($element)
    {
        return call_user_func_array('array_push', array_merge([&$this->array], func_get_args()));
    }

    /**
     * Reverses the order of the elements of an array -- the first becomes the last, and the last becomes the first.
     * @mutator
     * @return void
     */
    public function reverse()
    {
        $this->array = array_reverse($this->array);
    }

    /**
     * Removes the first element from an array and returns that element.
     * @mutator
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->array);
    }

    /**
     * Adds and/or removes elements from an array.
     * @mutator
     * @param int $index
     * @param int $howMany
     * @param mixed $replacement,...
     * @return Collection
     */
    public function splice($index, $howMany = null, $replacement = null)
    {
        if (func_num_args() === 1) {
            $removed = array_splice($this->array, $index);
        } elseif (func_num_args() === 2) {
            $removed = array_splice($this->array, $index, $howMany);
        } else {
            $replacements = array_slice(func_get_args(), 2);
            $removed = array_splice($this->array, $index, $howMany, $replacements);
        }

        return new self($removed);
    }

    /**
     * Adds one or more elements to the front of an array and returns the new length of the array.
     * @mutator
     * @param mixed $element,...
     * @return int
     */
    public function unshift($element)
    {
        return call_user_func_array('array_unshift', array_merge([&$this->array], func_get_args()));
    }

    /**
     * Joins all elements of an array into a string.
     * @accessor
     * @param string $separator
     * @return string
     */
    public function join($separator = ',')
    {
        return implode($separator, $this->array);
    }

    /**
     * Extracts a section of an array and returns a new array.
     * @accessor
     * @param int $begin
     * @param int $end
     * @return Collection
     */
    public function slice($begin, $end = null)
    {
        $output = call_user_func_array('array_slice', array_merge([$this->array], func_get_args()));
        return new self($output);
    }

    /**
     * Return an array of the collection.
     * @accessor
     * @return array
     */
    public function toArray()
    {
        return $this->array;
    }

    /**
     * Returns the first (least) index of an element within the array equal to the specified value, or -1 if none is found.
     *
     * indexOf compares searchElement to elements of the Array using strict equality (the same method used by the ===, or triple-equals, operator).
     *
     * @accessor
     * @param mixed $element
     * @param int $fromIndex The index at which to begin the search. Defaults to 0
     * @return int
     */
    public function indexOf($element, $fromIndex = 0)
    {
        foreach ($this->array as $index => $value) {
            if ($fromIndex > $index) {
                continue;
            }

            if ($value === $element) {
                return $index;
            }
        }

        return -1;
    }

    /**
     * Creates a new array with all of the elements of this array for which the provided filtering function returns true.
     * @iteration
     * @param callable $callback
     * @return Collection
     */
    public function filter(callable $callback)
    {
        $filtered = array_filter($this->array, function ($element) use ($callback) {
            static $index = -1;
            return $callback($element, $index += 1, $this);
        });
        $filtered = array_values($filtered);

        return new self($filtered);
    }

    /**
     * Returns true if every element in this array satisfies the provided testing function.
     * @iteration
     * @param callable $callback
     * @return bool
     */
    public function every(callable $callback)
    {
        foreach ($this->array as $index => $element) {
            if ($callback($element, $index, $this) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Creates a new array with the results of calling a provided function on every element in this array.
     * @iteration
     * @param callable $callback
     * @return Collection
     */
    public function map(callable $callback)
    {
        $mapped = array_map($callback, $this->array);
        return new self($mapped);
    }

    /**
     * Returns true if at least one element in this array satisfies the provided testing function.
     * @iteration
     * @param callable $callback
     * @return bool
     */
    public function some(callable $callback)
    {
        foreach ($this->array as $index => $element) {
            if ($callback($element, $index, $this)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Apply a function simultaneously against two values of the array (from left-to-right) as to reduce it to a single value.
     * @iteration
     * @param callable $callback
     * @param mixed    $initialValue
     * @return mixed
     */
    public function reduce(callable $callback, $initialValue = null)
    {
        return array_reduce($this->array, $callback, $initialValue);
    }

    /**
     * Return an element of index
     * @accessor
     * @param int $index
     * @return mixed
     */
    public function get($index)
    {
        return $this->array[$index];
    }

    /**
     * Determine if the element in this collection
     * @param mixed $element
     * @return bool
     */
    public function contains($element)
    {
        return in_array($element, $this->array, true);
    }

    /**
     * Retrieve an external iterator
     * @iteration
     * @return Traversable An instance of an object implementing Iterator or Traversable
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }

    /**
     * Remove an element. If collection contains some same elements, the first matched element will be removed.
     * @mutator
     * @param mixed $element
     */
    public function remove($element)
    {
        array_splice($this->array, $this->indexOf($element), 1);
    }

    /**
     * Count elements of an object
     * @accessor
     * @return int The custom count as an integer.
     *             The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->array);
    }

    /**
     * Remove all elements from the deque leaving it with length 0.
     * @mutator
     * @return void
     */
    public function clear()
    {
        $this->array = [];
    }

    /**
     * @mutator
     * @param array|Collection $array
     * @return void
     */
    public function concat($array)
    {
        foreach ($array as $element) {
            $this->push($element);
        }
    }

    /**
     * Shuffle collection
     * @mutator
     * @return void
     */
    public function shuffle()
    {
        shuffle($this->array);
    }
}
