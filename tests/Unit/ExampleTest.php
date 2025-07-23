<?php

namespace Tests\Unit;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function testTrueIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testFalseIsFalse()
    {
        $this->assertFalse(false);
    }

    public function testDummyEquality()
    {
        $this->assertEquals(1, 1);
    }

    public function testStringContains()
    {
        $this->assertStringContainsString('foo', 'foobar');
    }
}