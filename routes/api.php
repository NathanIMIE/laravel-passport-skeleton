<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
|--------------------------------------------------------------------------
| SessionController routes
|--------------------------------------------------------------------------
|
| Those routes must be used by local API clients, see SessionController.
|
*/
Route::post('/token', 'SessionController@createToken')
    ->name('api.session.token.create');

Route::post('/token/refresh', 'SessionController@refreshToken')
    ->name('api.session.token.refresh');

Route::middleware('auth:api')
    ->delete('/token', 'SessionController@destroyToken')
    ->name('api.session.token.destroy');

Route::middleware('auth:api')
    ->get('/user', 'SessionController@getUser')
    ->name('api.session.user');

Route::post('/user', 'UserController@createUser')
    ->name('api.user.create');

Route::middleware('auth:api')
    ->delete('/user', 'UserController@deleteUser')
    ->name('api.user.delete');

Route::middleware('auth:api')
    ->post('/ticket', 'TicketsController@createTicket')
    ->name('api.ticket.create');

Route::middleware('auth:api')
    ->put('/ticket/{id}', 'TicketsController@updateTicket')
    ->name('api.ticket.update');

Route::middleware('auth:api')
    ->delete('/ticket/{id}', 'TicketsController@deleteTicket')
    ->name('api.ticket.delete');

Route::middleware('auth:api')
    ->put('/ticket/assign/{id}', 'TicketsController@addAssignTicket')
    ->name('api.ticket.create.assign');

Route::middleware('auth:api')
    ->delete('/ticket/assign/{id}', 'TicketsController@deleteAssignTicket')
    ->name('api.ticket.delete.assign');

Route::middleware('auth:api')
    ->put('/ticket/start/{id}', 'TicketsController@startTicket')
    ->name('api.ticket.start');

Route::middleware('auth:api')
    ->put('/ticket/finish/{id}', 'TicketsController@finishTicket')
    ->name('api.ticket.finish');

Route::middleware('auth:api')
    ->post('/ticket/comment/{id}', 'TicketsController@commentTicket')
    ->name('api.ticket.comment');

Route::middleware('auth:api')
    ->delete('/ticket/comment/{id}', 'TicketsController@uncommentTicket')
    ->name('api.ticket.uncomment');

Route::middleware('auth:api')
    ->get('/ownedTickets', 'TicketsController@getOwnedTickets')
    ->name('api.tickets.getOwned');

Route::middleware('auth:api')
    ->get('/assignedTickets', 'TicketsController@getAssignedTickets')
    ->name('api.tickets.getAssigned');

Route::middleware('auth:api')
    ->get('/ticket/{id}', 'TicketsController@getTicket')
    ->name('api.ticket.get');