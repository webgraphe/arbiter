<?php

namespace Webgraphe\Tests\Arbiter;

use Exception;
use Webgraphe\Arbiter\Contracts\PredicateContract;
use Webgraphe\Arbiter\PredicateSet;

class PredicateSetTest extends TestCase
{
    public function testBareNewSet()
    {
        $set = new PredicateSet();

        $this->assertTrue($set->isEmpty());
        $this->assertCount(0, $set);
        $this->assertNull($set->get('undefined'));
        $this->assertEmpty($set->names());
    }

    public function testSetWithPredicates()
    {
        ['true' => $true, 'false' => $false]
            = $predicates
            = ['true' => $this->truePredicate(), 'false' => $this->falsePredicate()];
        $this->assertNotEquals($true, $false);

        $set = (new PredicateSet())->put('true', $true)->put('false', $false);

        $this->assertFalse($set->isEmpty());
        $this->assertCount(2, $set);
        $this->assertEquals(array_keys($predicates), $set->names());
        $iterated = [];
        foreach ($set as $name => $predicate) {
            $iterated[$name] = $predicate;
            $this->assertEquals($set->get($name), $predicate);
        }
        $this->assertEquals($predicates, $iterated);
    }

    public function testImmutability()
    {
        $empty = new PredicateSet();
        $set = $empty->put('true', $this->truePredicate());

        $this->assertNotEquals($empty, $set);

        try {
            foreach ($set as &$reference) {
                $reference = $this->falsePredicate();
            }
            $this->fail("Tolerated yielding by reference");
        } catch (Exception) {
        }
    }

    private function truePredicate(): PredicateContract
    {
        return new class implements PredicateContract {
            public function evaluate(): bool
            {
                return true;
            }
        };
    }

    private function falsePredicate(): PredicateContract
    {
        return new class implements PredicateContract {
            public function evaluate(): bool
            {
                return false;
            }
        };
    }
}
