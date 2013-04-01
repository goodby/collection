<?php

namespace Goodby\Collection\Tests\Unit;

use Goodby\Collection\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function testPop()
    {
        $collection = new Collection(['first', 'second', 'last']);
        $popped = $collection->pop();
        $this->assertSame('last', $popped);
        $this->assertSame(['first', 'second'], $collection->toArray());
    }

    public function testPush()
    {
        $collection = new Collection(['first', 'second']);
        $length = $collection->push('third');
        $this->assertSame(3, $length);
        $this->assertSame(['first', 'second', 'third'], $collection->toArray());

        $length = $collection->push('4th', '5th');
        $this->assertSame(5, $length);
        $this->assertSame(['first', 'second', 'third', '4th', '5th'], $collection->toArray());
    }

    public function testReverse()
    {
        $collection = new Collection(['1st', '2nd', '3rd']);
        $collection->reverse();
        $this->assertSame(['3rd', '2nd', '1st'], $collection->toArray());
    }

    public function testShift()
    {
        $collection = new Collection(['1st', '2nd', '3rd']);
        $shifted = $collection->shift();
        $this->assertSame('1st', $shifted);
        $this->assertSame(['2nd', '3rd'], $collection->toArray());
    }

    public function testSplice()
    {
        $collection = new Collection(['red', 'green', 'blue', 'yellow']);
        $removed = $collection->splice(2);
        $this->assertSame(['red', 'green'], $collection->toArray());
        $this->assertTrue($removed instanceof Collection);
        $this->assertSame(['blue', 'yellow'], $removed->toArray());

        $collection = new Collection(['red', 'green', 'blue', 'yellow']);
        $collection->splice(1, -1);
        $this->assertSame(['red', 'yellow'], $collection->toArray());

        $collection = new Collection(['red', 'green', 'blue', 'yellow']);
        $collection->splice(1, 4, 'orange');
        $this->assertSame(['red', 'orange'], $collection->toArray());

        $collection = new Collection(['red', 'green', 'blue', 'yellow']);
        $collection->splice(-1, 1, 'black', 'maroon');
        $this->assertSame(['red', 'green', 'blue', 'black', 'maroon'], $collection->toArray());

        $collection = new Collection(['red', 'green', 'blue', 'yellow']);
        $collection->splice(3, 0, 'purple');
        $this->assertSame(['red', 'green', 'blue', 'purple', 'yellow'], $collection->toArray());
    }

    public function testUnshift()
    {
        $collection = new Collection(['1st', '2nd', '3rd']);
        $length = $collection->unshift('zero');
        $this->assertSame(4, $length);
        $this->assertSame(['zero', '1st', '2nd', '3rd'], $collection->toArray());

        $collection = new Collection([0, 1, 2]);
        $length = $collection->unshift(-2, -1);
        $this->assertSame(5, $length);
        $this->assertSame([-2, -1, 0, 1, 2], $collection->toArray());
    }

    public function testJoin()
    {
        $collection = new Collection(['a', 'b', 'c']);
        $this->assertSame('a,b,c', $collection->join());

        $collection = new Collection(['a', 'b', 'c']);
        $this->assertSame('a+b+c', $collection->join('+'));
    }

    public function testSlice()
    {
        $collection = new Collection(['a', 'b', 'c', 'd', 'e']);
        $this->assertSame(['c', 'd', 'e'], $collection->slice(2)->toArray());
        $this->assertSame(['d'], $collection->slice(-2, 1)->toArray());
        $this->assertSame(['a', 'b', 'c'], $collection->slice(0, 3)->toArray());
    }

    public function testIndexOf()
    {
        $collection = new Collection(['a', 'b', 'a', 'a']);
        $this->assertSame(0, $collection->indexOf('a'));
        $this->assertSame(-1, $collection->indexOf('non-member'));
        $this->assertSame(2, $collection->indexOf('a', 1));
        $this->assertSame(-1, $collection->indexOf('a', 100));
    }

    public function testFilter()
    {
        $isBigEnough = function ($element, $index, Collection $array) {
            $this->assertSame($element, $array->get($index));
            return ($element >= 10);
        };

        $collection = new Collection([12, 5, 8, 130, 44]);
        $filtered = $collection->filter($isBigEnough);
        $this->assertSame([12, 130, 44], $filtered->toArray());
    }

    public function testEvery()
    {
        $isBigEnough = function ($element, $index, Collection $array) {
            $this->assertSame($element, $array->get($index));
            return ($element >= 10);
        };

        $collection = new Collection([12, 5, 8, 130, 44]);
        $this->assertFalse($collection->every($isBigEnough));

        $collection = new Collection([12, 54, 18, 130, 44]);
        $this->assertTrue($collection->every($isBigEnough));
    }

    public function testMap()
    {
        $collection = new Collection(['1', '2', '3']);
        $mapped = $collection->map('intval');
        $this->assertSame([1, 2, 3], $mapped->toArray());
    }

    public function testSome()
    {
        $isBigEnough = function ($element, $index, Collection $array) {
            return ($element >= 10);
        };

        $collection = new Collection([2, 5, 8, 1, 4]);
        $passed = $collection->some($isBigEnough);
        $this->assertFalse($passed);

        $collection = new Collection([12, 5, 8, 1, 4]);
        $passed = $collection->some($isBigEnough);
        $this->assertTrue($passed);
    }

    public function testReduce()
    {
        $collection = new Collection([0, 1, 2, 3]);
        $total = $collection->reduce(function ($a, $b) {
            return $a + $b;
        });
        $this->assertSame(6, $total);

        $total = $collection->reduce(function ($a, $b) {
            return $a + $b;
        }, 10);
        $this->assertSame(16, $total);
    }

    public function testContains()
    {
        $collection = new Collection([1, 2, 3]);
        $contains = $collection->contains(2);
        $this->assertTrue($contains);

        $contains = $collection->contains('1');
        $this->assertFalse($contains);
    }

    public function testForEach()
    {
        $collection = new Collection(['a', 'b', 'c']);

        $looped = [];

        foreach ($collection as $key => $value) {
            $looped[$key] = $value;
        }

        $this->assertSame(['a', 'b', 'c'], $looped);
    }

    public function testRemove()
    {
        $collection = new Collection(['a', 'b', 'c', 'a']);
        $collection->remove('a');
        $this->assertSame(['b', 'c', 'a'], $collection->toArray());
        $collection->remove('c');
        $this->assertSame(['b', 'a'], $collection->toArray());
    }

    public function testSize()
    {
        $collection = new Collection([1, 2, 3]);
        $this->assertSame(3, $collection->count());
    }

    public function testClear()
    {
        $collection = new Collection([1, 2, 3]);
        $collection->clear();
        $this->assertSame(0, $collection->count());
    }

    public function testConcat()
    {
        $collection = new Collection([1, 2, 3]);
        $collection->concat([3, 4, 5]);
        $this->assertSame([1, 2, 3, 3, 4, 5], $collection->toArray());
    }

    public function testShuffle()
    {
        $collection = new Collection([1, 2, 3, 4, 5, 6, 7, 8, 9]);
        $collection->shuffle();
        $this->assertNotEquals([1, 2, 3, 4, 5, 6, 7, 8, 9], $collection->toArray());
    }
}
