<?php

use Illuminate\Support\Facades\Route;

Route::post('update-json/{module}/{key}/{value}', function($module, $key, $value) {
    $result = update_json_value($module, $key, $value);
    return $result;
});
