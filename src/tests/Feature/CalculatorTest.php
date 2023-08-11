<?php

namespace Tests\Feature;

use App\Libraries\Calculator;
use App\Services\MathService;
use Tests\TestCase;

class CalculatorTest extends TestCase
{
    public function testAdd()
    {
        // Tạo đối tượng giả lập MathService
        $mathServiceMock = $this->createMock(MathService::class);
        $mathServiceMock->expects($this->once())
            ->method('add')
            ->with(2, 3)
            ->willReturn(5);

        // Tạo đối tượng Calculator với đối tượng giả lập MathService
        $calculator = new Calculator($mathServiceMock);

        // Gọi phương thức add() và kiểm tra kết quả
        $result = $calculator->add(2, 3);
        $this->assertEquals(5, $result);
    }
}