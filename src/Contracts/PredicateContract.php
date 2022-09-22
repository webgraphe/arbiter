<?php

namespace Webgraphe\Arbiter\Contracts;

interface PredicateContract
{
    public function evaluate(): bool;
}
