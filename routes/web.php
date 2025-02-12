<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;
use App\Models\Member;
use Illuminate\Support\Arr;

Route::get('/adm-dsh', function () {
    return view('adm_dashboard', [
        "greeting" => "This is dashboard"
    ]);
});

Route::get('/customers', function (){
   return view('adm_customers');
});

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::patch('/products/update', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::post('/products', [ProductController::class, 'store'])->name('product.store');

Route::get('/customers', [ShopController::class, 'showCustomers']) -> name('customers.show');
Route::delete('/customers/{id}', [ShopController::class, 'destroy']) -> name('customers.destroy');
Route::patch('/customers/update', [ShopController::class, 'update']) -> name('customers.update');
Route::post('/customers', [ShopController::class, 'store']) -> name('customers.store');

// Route::get('/inventory', [InventoryController::class, 'data-show']) -> name('inventory.show');

Route::get('/inventory', function () {
    return view('adm_inventory', [
        "greeting" => "This is inventory page"
    ]);
});

Route::get('/orders', function (){
    return view('adm_orders', [
        "greeting" => "This is orders page"
    ]);
});

Route::get('/members', function (){
    return view('members', [
        "members" => Member::all()
    ]);
});

Route::get('/members/{id}', function($id)  {

    $member = Member::find($id);

    return view('member', [
        "member" => $member
    ]);
});

// Below Code are Submitted By Shota-Kun

// Sales URL For Get
Route::get('/sales', function (){
    return view('adm_invoices', [
        "greeting" => "This is orders page"
    ]);
});

//Routes For CSV.DOWNLOAD
use App\Http\Controllers\StockController;
Route::get('/download-stock-csv', [StockController::class, 'downloadCSV'])->name('stock.downloadCSV');

// Routes For ChartController
use App\Http\Controllers\ChartController;
Route::get('/adm-dsh', [ChartController::class, 'index'])->name('admin.chart');

// Getting the Stock Data Controller
use App\Http\Controllers\StockDataController;

// Define a route for fetching the stock data
Route::get('/get-stock-data', [StockDataController::class, 'getStockData']);

// Sales Invoices Showing Off
use App\Http\Controllers\SaleController;

Route::get('/invoice/{invoiceNo}', [SaleController::class, 'getInvoiceDetails']);
Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
Route::get('invoice/details/{invoice_no}', [SaleController::class, 'showInvoiceDetails'])->name('invoice.details');

// Driver Login Blade
Route::get('/driver_login', function () {
    return view('driver_login');
});

// Driver Dashboard Blade
Route::get('/driver_dashboard', function () {
    return view('driver_dashboard');
});

