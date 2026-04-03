<?php 

use Illuminate\Support\Facades\Route;
use Contus\Organizations\Http\Controllers\OrganizationController;

Route::get('admin/organizations', [OrganizationController::class, 'index']);