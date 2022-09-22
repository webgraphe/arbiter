<?php

declare(strict_types=1);

namespace Webgraphe\Arbiter;

use Countable;
use Generator;
use IteratorAggregate;
use Webgraphe\Arbiter\Contracts\PredicateContract;

/**
 * Immutable collection of predicates
 */
class PredicateSet implements Countable, IteratorAggregate
{
    /** @var array<string, PredicateContract> Associated by name */
    private array $predicates = [];

    public function isEmpty(): bool
    {
        return !count($this);
    }

    public function count(): int
    {
        return count($this->predicates);
    }

    /**
     * @param string $name
     * @param PredicateContract $predicate
     * @return static
     */
    public function put(string $name, PredicateContract $predicate): self
    {
        $clone = clone $this;
        $clone->predicates[$name] = $predicate;

        return $clone;
    }

    public function get(string $name): ?PredicateContract
    {
        return $this->predicates[$name] ?? null;
    }

    public function names(): array
    {
        return array_keys($this->predicates);
    }

    public function getIterator(): Generator
    {
        yield from $this->predicates;
    }
}
