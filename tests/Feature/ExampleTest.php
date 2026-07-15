<?php

it('returns a successful response', function () {
    $response = $this->get('/cp/login');

    $response->assertStatus(200);
});
