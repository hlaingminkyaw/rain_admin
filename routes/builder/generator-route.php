<?php

use Illuminate\Support\Facades\Route;

//routes
// Route::get('/health-check', function (\Illuminate\Http\Request $request) {
//     $token = $request->header('X-Health-Check-Token');

//     if ($token !== env('HEALTH_CHECK_TOKEN')) {
//         return response()->json([
//             'status' => 'forbidden',
//             'message' => 'Invalid or missing token',
//         ], 403);
//     }

//     return response()->json(['status' => 'ok']);
// });
Route::get('/health/orders', [App\Http\Controllers\Generators\OrderController::class, 'healthCheck']);


Route::group(['middleware' => ['permission:items,all_generated']], function () {
    Route::resource('items', App\Http\Controllers\Generators\ItemsController::class);
    Route::get('items_recently_deleted', 'App\Http\Controllers\Generators\ItemsController@recently_deleted');
    Route::get('items_restore_deleted/{id}', 'App\Http\Controllers\Generators\ItemsController@restore_deleted');
});
Route::group(['middleware' => ['permission:customerinfos,all_generated']], function () {
    Route::resource('customerinfos', App\Http\Controllers\Generators\CustomerinfoController::class);
    Route::get('customerinfos_recently_deleted', 'App\Http\Controllers\Generators\CustomerinfoController@recently_deleted');
    Route::get('customerinfos_restore_deleted/{id}', 'App\Http\Controllers\Generators\CustomerinfoController@restore_deleted');
});
Route::group(['middleware' => ['permission:itempricings,all_generated']], function () {
    Route::resource('itempricings', App\Http\Controllers\Generators\ItempricingController::class);
    Route::get('itempricings_recently_deleted', 'App\Http\Controllers\Generators\ItempricingController@recently_deleted');
    Route::get('itempricings_restore_deleted/{id}', 'App\Http\Controllers\Generators\ItempricingController@restore_deleted');
    Route::get('filter', 'App\Http\Controllers\Generators\ItempricingController@filter');
});
Route::group(['middleware' => ['permission:orders,all_generated']], function () {
    Route::resource('orders', App\Http\Controllers\Generators\OrderController::class);

    Route::get('orders_recently_deleted', 'App\Http\Controllers\Generators\OrderController@recently_deleted');
    Route::get('orders_restore_deleted/{id}', 'App\Http\Controllers\Generators\OrderController@restore_deleted');
    Route::get('/get-order-items', 'App\Http\Controllers\Generators\OrderController@getOrderItems');
    Route::get('logs/{order_id}', 'App\Http\Controllers\Generators\OrderController@showLogs')->name('logs.show');


});

Route::group(['middleware' => ['permission:transfer_request,all_generated']], function () {
    Route::get('transfer_requests', 'App\Http\Controllers\Generators\OrderController@transfer_requests')->name('transfer_requests');
    Route::get('missingitems', 'App\Http\Controllers\Generators\OrderController@missingItemsView')->name('missingitems.view');

    Route::get('transfer_request', 'App\Http\Controllers\Generators\OrderController@transfer_request')->name('transfer_request');

    Route::post('transfer_accept', 'App\Http\Controllers\Generators\OrderController@transfer_accept')->name('transfer_accept');
    Route::get('transfer_reject/{id}', 'App\Http\Controllers\Generators\OrderController@transfer_reject')->name('transfer_reject');

});


Route::group(['middleware' => ['permission:orderqty_records,all_generated']], function () {
    Route::resource('orderqty_records', App\Http\Controllers\Generators\Orderqty_recordController::class);
    Route::post('save-generated-items', 'App\Http\Controllers\Generators\Orderqty_recordController@saveGeneratedItems')->name('save.generated.items');

    Route::get('orderqty_records_recently_deleted', 'App\Http\Controllers\Generators\Orderqty_recordController@recently_deleted');
    Route::get('orderqty_records_restore_deleted/{id}', 'App\Http\Controllers\Generators\Orderqty_recordController@restore_deleted');
    Route::get('items_ward_placing', 'App\Http\Controllers\Generators\Orderqty_recordController@items_ward_placing')->name('orderqty_records.items_ward_placing');
    Route::get('items_ward_placing_report', 'App\Http\Controllers\Generators\Orderqty_recordController@report')->name('orderqty_records.items_ward_placing_report');
    Route::get('check_qc_list', 'App\Http\Controllers\Generators\Orderqty_recordController@check_qc_list')->name('orderqty_records.check_qc_list');
    Route::get('/orders/{order}/view', 'App\Http\Controllers\Generators\Orderqty_recordController@view')->name('orders.view');
    Route::get('delivery', 'App\Http\Controllers\Generators\Orderqty_recordController@delivery')->name('orderqty_records.delivery');
    Route::get('balance', 'App\Http\Controllers\Generators\Orderqty_recordController@balance')->name('orderqty_records.balance');
    Route::get('tailor', 'App\Http\Controllers\Generators\Orderqty_recordController@tailor')->name('orderqty_records.tailor'); 
    Route::get('tailor/{order_id}', 'App\Http\Controllers\Generators\Orderqty_recordController@tailorDetails')->name('orderqty_records.detail'); 

    Route::get('order-logs', 'App\Http\Controllers\Generators\Orderqty_recordController@logs')->name('orderqty_records.logs'); 
    Route::get('order-logs/{order_id}', 'App\Http\Controllers\Generators\Orderqty_recordController@logDetails')->name('orderqty_records.details'); 

  
    Route::post('tailor/restore', 'App\Http\Controllers\Generators\Orderqty_recordController@restore')->name('orderqty_records.restore');
    Route::get('/balance/view/{order_id}/{dept_id}', 'App\Http\Controllers\Generators\Orderqty_recordController@viewBalanceItems')->name('orderqty_records.balance.view');

    Route::post('orders/from-balance', 'App\Http\Controllers\Generators\OrderController@createFromBalance')->name('orders.createFromBalance');

    Route::post('delivery/store', 'App\Http\Controllers\Generators\Orderqty_recordController@storeDelivery')->name('delivery.store');
    Route::post('delivery/restore', 'App\Http\Controllers\Generators\Orderqty_recordController@restoreDelivery')->name('delivery.restore');
    Route::get('get_order_details', 'App\Http\Controllers\Generators\Orderqty_recordController@get_order_details')
    ->name('orderqty_records.get_order_details');

   Route::post('mark_qc_pass', 'App\Http\Controllers\Generators\Orderqty_recordController@mark_qc_pass')
    ->name('orderqty_records.mark_qc_pass');
    Route::get('qc_passed_list', 'App\Http\Controllers\Generators\Orderqty_recordController@qc_passed_list')->name('orderqty_records.qc_passed_list');
    Route::get('/qc/customers-by-date', 'App\Http\Controllers\Generators\Orderqty_recordController@customersByDate')->name('customers.by.date');

    Route::post('update-payment-status', 'App\Http\Controllers\Generators\Orderqty_recordController@updatePaymentStatus')->name('orderqty_records.update_payment_status');

    // Route::post('/update-payment-status', [YourController::class, 'updatePaymentStatus'])->name('orderqty_records.update_payment_status');

    Route::get('refund-list', 'App\Http\Controllers\Generators\Orderqty_recordController@refund_list')->name('refund.list');
    Route::post('refund-decision', 'App\Http\Controllers\Generators\Orderqty_recordController@handleRefundDecision')->name('refund.decision');

    Route::post('items_ward_store', 'App\Http\Controllers\Generators\Orderqty_recordController@items_ward_store')->name('items_ward.store');
    Route::get('pressing_dept', 'App\Http\Controllers\Generators\Orderqty_recordController@pressing_dept')->name('pressing_dept');
    Route::get('qc_dept', 'App\Http\Controllers\Generators\Orderqty_recordController@qc_dept')->name('qc_dept');

    Route::get('pickup_deli_records', 'App\Http\Controllers\Generators\Orderqty_recordController@pickup_deli_records')->name('pickup_deli_records');
    Route::post('/save-item', 'App\Http\Controllers\Generators\Orderqty_recordController@saveItem')->name('save.item');
    // Route::post('/damage-item', 'App\Http\Controllers\Generators\Orderqty_recordController@damageItem')->name('damage.item');

    Route::put('/update-item', 'App\Http\Controllers\Generators\Orderqty_recordController@updateItem')->name('update.item');
    Route::delete('/delete-item', 'App\Http\Controllers\Generators\Orderqty_recordController@deleteItem')->name('delete.item');
    // Route::post('/items_ward/store', 'App\Http\Controllers\Generators\Orderqty_recordController@itemStore')->name('items_ward.store');
    Route::post('/missing-items/store', 'App\Http\Controllers\Generators\Orderqty_recordController@storeMissingItems')->name('missing_items.store');

});

// Route::get('check_qc_list', 'App\Http\Controllers\Generators\Orderqty_recordController@check_qc_list')
//     ->name('orderqty_records.check_qc_list')
//     ->middleware('permission:orderqty_records.check_qc_list');

Route::group(['middleware' => ['permission:cars,all_generated']], function () {
    Route::resource('cars', App\Http\Controllers\Generators\CarsController::class);
    Route::get('cars_recently_deleted', 'App\Http\Controllers\Generators\CarsController@recently_deleted');
    Route::get('cars_restore_deleted/{id}', 'App\Http\Controllers\Generators\CarsController@restore_deleted');
});
Route::group(['middleware' => ['permission:assign_orders,all_generated']], function () {
    Route::get('assign_orders', [App\Http\Controllers\Generators\AssignOrdersController::class, 'show'])->name('assign_orders.show');

    Route::resource('assign_orders', App\Http\Controllers\Generators\AssignOrdersController::class);
    Route::get('assign_orders_recently_deleted', 'App\Http\Controllers\Generators\AssignOrdersController@recently_deleted');
    Route::get('assign_orders_restore_deleted/{id}', 'App\Http\Controllers\Generators\AssignOrdersController@restore_deleted');
});
Route::group(['middleware' => ['permission:processingrecords,all_generated']], function () {
    Route::resource('processingrecords', App\Http\Controllers\Generators\ProcessingrecordsController::class);
    Route::get('departments', 'App\Http\Controllers\Generators\ProcessingrecordsController@departments')->middleware('department.permission:department')->name('departments');
    Route::get('view_missing', 'App\Http\Controllers\Generators\ProcessingrecordsController@viewMissing')->middleware('department.permission:department')->name('view_missing');

    // Route::get('/view_missing', [YourController::class, 'viewMissing'])->name('view_missing');

    Route::post('departments_process', 'App\Http\Controllers\Generators\ProcessingrecordsController@departments_process')->middleware('department.permission:department')->name('departments_process');
    Route::post('/store-missing-items', 'App\Http\Controllers\Generators\ProcessingrecordsController@storeItems')->name('store.missing.items');
    Route::post('/damage-items', 'App\Http\Controllers\Generators\ProcessingrecordsController@damageItems')->name('store.damage.items');
    Route::post('/log/transfer-action',  'App\Http\Controllers\Generators\ProcessingrecordsController@storeLogs')->name('log.transfer.action');
    Route::post('/log/back-action',  'App\Http\Controllers\Generators\ProcessingrecordsController@transferAction')->name('log.transfer.action');

    
    Route::get('/check-remaining-items', 'App\Http\Controllers\Generators\ProcessingrecordsController@checkRemainingItems')->name('check_remaining_items');
    Route::post('/restore-missing-items', 'App\Http\Controllers\Generators\ProcessingrecordsController@restoreMissingItems')->name('restore.missing.items');

    
    Route::get('qc_process/{order_id}/{item_id}', 'App\Http\Controllers\Generators\ProcessingrecordsController@qc_process')->middleware('department.permission:dept-08')->name('qc_process');
    Route::get('process_item_info/{order_id}/{item_id}', 'App\Http\Controllers\Generators\ProcessingrecordsController@process_item_info')->middleware('department.permission:dept-08')->name('process_item_info');    
    Route::post('qc_transfer', 'App\Http\Controllers\Generators\ProcessingrecordsController@qc_transfer')->middleware('department.permission:dept-08')->name('qc_transfer');   

    Route::post('record_stain', 'App\Http\Controllers\Generators\ProcessingrecordsController@record_stain')->name('record_stain');   

    Route::post('/stain-transfer', 'App\Http\Controllers\Generators\ProcessingrecordsController@stainTransfer')->name('stain_transfer');
    Route::get('/transfer-list', 'App\Http\Controllers\Generators\ProcessingrecordsController@getTransferList')->name('transfer.list');
    Route::get('previous_order_balance_check','App\Http\Controllers\Generators\ProcessingrecordsController@previous_order_balance_check')->name('previous_order_balance_check');

    Route::get('order_processing_history','App\Http\Controllers\Generators\ProcessingrecordsController@order_processing_history')->name('order_processing_history');

    Route::get('missing_items','App\Http\Controllers\Generators\ProcessingrecordsController@missing_items')->name('missing_items');

    Route::get('processingrecords_recently_deleted', 'App\Http\Controllers\Generators\ProcessingrecordsController@recently_deleted');
    Route::get('processingrecords_restore_deleted/{id}', 'App\Http\Controllers\Generators\ProcessingrecordsController@restore_deleted');
});
Route::group(['middleware' => ['permission:drivers,all_generated']], function () {
    Route::resource('drivers', App\Http\Controllers\Generators\DriversController::class);
    Route::get('drivers_recently_deleted', 'App\Http\Controllers\Generators\DriversController@recently_deleted');
    Route::get('drivers_restore_deleted/{id}', 'App\Http\Controllers\Generators\DriversController@restore_deleted');
});

Route::group(['middleware' => ['permission:invoicing,all_generated']], function () {
    Route::get('invoicing','App\Http\Controllers\Generators\OrderController@invoicing')->name('invoicing');
    Route::get('generate_invoice','App\Http\Controllers\Generators\OrderController@generate_invoice')->name('generate_invoice');
});

Route::group(['middleware' => ['permission:imageattachments,all_generated']], function () {
    Route::resource('imageattachments', App\Http\Controllers\Generators\ImageattachmentController::class);
    Route::get('imageattachments_recently_deleted', 'App\Http\Controllers\Generators\ImageattachmentController@recently_deleted');
    Route::get('imageattachments_restore_deleted/{id}', 'App\Http\Controllers\Generators\ImageattachmentController@restore_deleted');
});



Route::group(['middleware' => ['permission:customer_orders,all_generated']], function () {
    Route::resource('customer_orders', App\Http\Controllers\Generators\CustomerOrderController::class);
    Route::get('customer_orders_recently_deleted', 'App\Http\Controllers\Generators\CustomerOrderController@recently_deleted');
    Route::get('customer_orders_restore_deleted/{id}', 'App\Http\Controllers\Generators\CustomerOrderController@restore_deleted');
});