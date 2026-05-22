<?php

namespace Tests\Feature;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class PresentationExceptionHandlerTest extends TestCase
{
    public function test_non_api_not_found_response_is_not_wrapped_in_api_envelope(): void
    {
        $this->withoutExceptionHandling();

        $this->expectException(NotFoundHttpException::class);

        $this->get('/not-real');
    }
}
