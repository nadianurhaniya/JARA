<?php

test('halaman kuis /jara dapat diakses', function () {
    $response = $this->get(route('jara.app'));

    $response->assertOk();
    $response->assertSee('jara-app');
});
