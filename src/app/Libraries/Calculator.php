<?php
namespace App\Libraries;

use App\Services\MathService;

class Calculator
{
    protected $mathService;

    public function __construct(MathService $mathService)
    {
        $this->mathService = $mathService;
    }

    public function add($a, $b)
    {
        return $this->mathService->add($a, $b);
    }
}


