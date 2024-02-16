<?php

use App\Jobs\SlowJob;
use App\Http\Middleware\Subscribed;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\SubscribedToPlan;
use App\Http\Controllers\API\LinkedinController;
use App\Http\Controllers\Stripe\PlanController;
use App\Http\Controllers\Stripe\CheckoutController;
use App\Http\Controllers\Stripe\SubscriptionController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\UserController;
use App\Http\Controllers\UserManagement\ProductController;
use App\Http\Controllers\UserManagement\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();
//Language Translation
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);

Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

Route::group(['middleware' => ['auth']], function () {

    Route::controller(LinkedinController::class)->group(function (){
        Route::get('auth/linkedin', 'signin')->name('auth_linkedin');
        Route::get('/linkedin/callback', 'callback')->name('callback');
        Route::post('/disconnet', 'disconnect')->name('disconnect_linkedin');
    });


    // Role functionality
    Route::controller(RoleController::class)->group(function () {
        // SlowJob::dispatch();
        Route::prefix('roles')->group(function () {
            // sleep(10);
            Route::get('/list', 'index')->name('index.page')->middleware('can:Role list');
            Route::get('/permissions/{id}', 'sh_pr')->name('role.pr')->middleware('can:Role list');
            Route::post('/store', 'store')->name('role.store')->middleware('can:Role create');
            Route::get('/edit/{id}', 'edit')->name('role.edit')->middleware('can:Role edit');
            Route::patch('/update/{id}', 'update')->name('role.update')->middleware('can:Role edit');
            Route::delete('/delete/{id}', 'destroy')->name('role.distroy')->middleware('can:Role delete');
        });
    });

    // user functionality
    Route::controller(UserController::class)->group(function () {
        Route::prefix('user')->group(function () {
            Route::get('/list', 'index')->name('user.index')->middleware('can:User list');
            Route::post('/store', 'store')->name('user.store')->middleware('can:User create');
            Route::get('/edit/{id}', 'edit')->name('user.edit')->middleware('can:User edit');
            Route::patch('/update/{id}', 'update')->name('user.update')->middleware('can:User edit');
            Route::delete('/delete/{id}', 'destroy')->name('user.destroy')->middleware('can:User delete');
            Route::get('excel', function () {
                return view('excel');
            })->middleware('can:User list');
            Route::get('export-user', 'exportUser')->name('export-user');
            Route::post('import-user', 'importUser')->name('import-user');
        });
    });

    // product functionality
    Route::controller(ProductController::class)->group(function () {
        Route::prefix('product')->group(function () {

            Route::get('/list', 'index')->name('product.index')->middleware('can:Product list');
            Route::post('/store', 'store')->name('product.store')->middleware('can:Product create');
            // Route::post('/store', 'store')->name('product.store')->middleware('can:Product create', 'productrestrict');
            Route::get('/edit/{id}', 'edit')->name('product.edit')->middleware('can:Product edit');
            Route::patch('/update/{id}', 'update')->name('product.update')->middleware('can:Product edit');
            Route::delete('/delete/{id}', 'destroy')->name('product.destroy')->middleware('can:Product delete');
            Route::get('excel', function () {
                return view('Product-Management.Products.excel');
            })->name('show-product-excel')->middleware('can:Product list');
            Route::get('export-product', 'exportproduct')->name('export-product');
            Route::post('import-product', 'importproduct')->name('import-product');
            Route::get('/generate-pdf',  'downloadpdf')->name('generate-pdf');
        });
    });

    Route::post('/products/{product}/pay', [CheckoutController::class, 'makePayment'])->name('products.pay');


    //----------------------------------- Stripe comments, will be used if needed--------------------------------

    // Route::get('/checkout/{product}', [CheckoutController::class, 'checkout'])->name('checkout');
    // Route::post('/checkout/{product}', [CheckoutController::class, 'charge'])->name('checkout.charge');

    // Route::get('export-user', [UserController::class, 'exportUser'])->name('export-user');
    // Route::post('import-user', [UserController::class, 'importUser'])->name('import-user');

    // Route::controller(PlanController::class)->group(function(){
    //     Route::get('/plans','index')->name('main-plans');
    //     Route::get('/plans/{plan}', 'show')->name("plans.show");
    //     Route::post('/subscription', 'subscription')->name("subscription.create");

    // });

    // -----------------------------------------------------------------------------------------------------------

    Route::controller(SubscriptionController::class)->group(function () {
        Route::middleware([SubscribedToPlan::class])->group(function () {
            Route::get('plans/create', 'showPlanForm')->name('plans.create');
        });
        Route::post('plans/store', 'savePlan')->name('plans.store');

        //-----------Stripe Routes-------------------
        Route::get('plans', 'allPlans')->name('plans.all');
        Route::post('plans/checkout', 'checkout')->name('plans.checkout');
        Route::post('plans/process', 'processPlan')->name('plan.process');
        //-------------------------------------------

        Route::get('subscriptions/all', 'allSubscriptions')->name('subscriptions.all');
        Route::get('subscriptions/cancel', 'cancelSubscriptions')->name('subscriptions.cancel');
        Route::get('subscriptions/resume', 'resumeSubscriptions')->name('subscriptions.resume');

        Route::get('plans/update', 'updateplans')->name('plans.all.update');

        Route::get('plans/update/{subscriptionName}', 'updateSubscription')->name('plans.update.subscription');
        Route::post('plans/update/process', 'processUpdate')->name('plans.update.process');


        Route::get('invoices', function () {
                return view('stripe.subscriptions.invoices');
        })->name('invoices.all');
    });
});


//Update User Details
Route::get('profile/view', [ProfileController::class, 'getprofile']);
Route::get('profile/edit/page', [ProfileController::class, 'viewedit']);
Route::post('profile/edit/store', [ProfileController::class, 'store'])->name('store.profile');

// Route::post('/update-profile/{id}', [App\Http\Controllers\HomeController::class, 'updateProfile'])->name('updateProfile');
// Route::post('/update-password/{id}', [App\Http\Controllers\HomeController::class, 'updatePassword'])->name('updatePassword');

Route::get('{any}', [App\Http\Controllers\HomeController::class, 'index'])->name('index');
