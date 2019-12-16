<?php

namespace Tests;

class ExampleTest extends TestCase
{
    public function testMainPage()
    {
        $response = $this->call('GET', '/');

        $this->assertEquals(200, $response->status());
    }
}
