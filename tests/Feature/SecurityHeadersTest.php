<?php

namespace Tests\Feature;

use Tests\TestCase;

class SecurityHeadersTest extends TestCase
{
    public function test_hsts_header_added_for_https_requests(): void
    {
        $this->get('https://example.test/')
            ->assertHeader(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
    }

    public function test_hsts_header_not_added_for_http_requests(): void
    {
        $this->get('/')
            ->assertHeaderMissing('Strict-Transport-Security');
    }
}

