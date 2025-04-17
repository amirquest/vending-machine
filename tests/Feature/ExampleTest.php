<?php

namespace Tests\Feature;

use Tests\FeatureTestCase;

class ExampleTest extends FeatureTestCase
{

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
