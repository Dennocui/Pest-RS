<?php

Route::redirect('/', '/login');
Route::redirect('/home', '/admin');
Auth::routes();

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Pests
    Route::delete('pests/destroy', 'PestController@massDestroy')->name('pests.massDestroy');
    Route::post('pests/media', 'PestController@storeMedia')->name('pests.storeMedia');
    Route::resource('pests', 'PestController');

    // Categories
    Route::delete('categories/destroy', 'CategoriesController@massDestroy')->name('categories.massDestroy');
    Route::resource('categories', 'CategoriesController');

    // Uploads
    Route::delete('uploads/destroy', 'UploadsController@massDestroy')->name('uploads.massDestroy');
    Route::post('uploads/media', 'UploadsController@storeMedia')->name('uploads.storeMedia');
    Route::resource('uploads', 'UploadsController');
});
