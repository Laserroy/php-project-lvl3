<?php

namespace Tests;

class MainPageTest extends TestCase
{
    public function testMainPage()
    {
        $response = $this->get(route('main_page'));
        $response->assertResponseStatus(200);
    }
}
