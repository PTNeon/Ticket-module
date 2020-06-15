<?php

Route::group([
    'namespace' => '\Modules\Ticket\Http\Controllers\V1',
    'prefix' => 'v1'
], function () {
    Route::post('/ticket/changestate', 'TicketController@ChangeStateTicket');

    Route::get('/ticket/imporance', 'TicketController@getImporanceList');
    Route::get('/ticket/subject', 'TicketController@getSubjectList');
    Route::get('/ticket/status', 'TicketController@getStatusList');

    Route::resource('tickets', 'TicketController');
    Route::resource('subtickets', 'SubTicketController');
});
