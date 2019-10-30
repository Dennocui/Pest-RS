<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:api']], function () {
    // Permissions
    Route::apiResource('permissions', 'PermissionsApiController');

    // Roles
    Route::apiResource('roles', 'RolesApiController');

    // Users
    Route::apiResource('users', 'UsersApiController');

    // Pests
    Route::post('pests/media', 'PestApiController@storeMedia')->name('pests.storeMedia');
    Route::apiResource('pests', 'PestApiController');

    // Categories
    Route::apiResource('categories', 'CategoriesApiController');

    // Uploads
    Route::post('uploads/media', 'UploadsApiController@storeMedia')->name('uploads.storeMedia');
    Route::apiResource('uploads', 'UploadsApiController');
});
