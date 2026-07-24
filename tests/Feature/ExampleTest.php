<?php

it('returns a successful response', function (): void {
    $response = $this->get('/cp/login');

    $response->assertStatus(200);
});
