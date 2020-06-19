<?php

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

use App\Http\Middleware\ActiveUser;
use App\Http\Resources\SalesReport;
use App\Loss;
use App\Product;
use App\Sale;
use App\SystemInfo;
use App\Tempsale;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    if(Auth::check()){
        return redirect('/main');
    }
    return redirect('/login');
})->middleware(['active']);


Route::get('capital',function(){
    $capital =  DB::table('inventories')->sum('cost');

    return "Total capital ".number_format($capital);
});
// Route::get('/newuser',function(){
// 	       $user = User::create([
//            'name'=>'Admin Admin', 
// 		   'username'=>'Admin', 
// 		   'password'=>Hash::make('admin'),
// 		   'role_id'=>'1',
// 		   'contact'=>'0622214774',
// 		   'isActive'=>1
//        ]);
// 	   return $user;
// });





Route::get('/pageNotFound',function(){
    abort(404);
});


Route::get('/suspended', function () {
    if(Auth::check()){
        if(Auth::user()->isActive==1){
            return redirect('/main');
        }else{
            return view('suspended');
        }
    }else{
        return redirect('/login');
    }

});

Route::get('/excel','ExportExcel@excel')->name('excel.excel');
Route::get('/print','ExportExcel@index');

Route::group(['middleware'=>['active']],function(){
    Route::post('/change/password   ',function (Request $request){
        if($request->password==$request->confirmPassword){
            $user = auth::user();
            $user->password = bcrypt($request->password);
            $user->save();
            return back()->with('passwordMessage','Password updated successfully');
        }else{
            return back()->with('passwordError','Password don\'t match');
        }
    });

    Route::get('/main',function (){
        if(Auth::check()){
            if(Auth::user()->role->name=='Manager'){

                $report = new SalesReport();
//                return response()->json($report);
                return view('main.index',compact('report'));
            }else{
                return redirect('/sale');
            }
        }
        return redirect('/login');
    });

    Route::get('/sale/cancel',function (){
        Tempsale::whereTNumber(session('TNumber'))->delete();
        Transaction::whereNumber(session('TNumber'))->delete();
        session()->forget(['TNumber','products']);
        return redirect('/sale')->with('message','Sale Canceled successfully');
    });
    /*
     * Confirm sale
     */
    Route::post('/sale/confirm','SaleController@confirm')->name('sale.confirm');
    /*
     * Add discount
     */
    Route::put('/sale/discount','SaleController@discount')->name('sale.discount');
    Route::resource('/sale','SaleController');
    Route::get('/expense','ExpenseController@index')->name('expense.index');
    Route::post('/expense','ExpenseController@store');
    Route::post('/expense/search','ExpenseController@show');
    Route::post('/expense/filter','ExpenseController@filter');
});




/*
 * Logout route
 */
Route::get('/logout', 'Auth\LoginController@logout')->name('logout' );

/*
 * Manager routes
 */
Route::group(['middleware'=>['manager']],function (){
    Route::get('/export/excel','UserController@export')->name('user.export');
    Route::resource('/user','UserController');
    Route::post('/product/loss','ProductController@loss')->name('product.loss');
    Route::resource('/product','ProductController');
    Route::post('/inventory','InventoryController@store');
    Route::get('/inventory','InventoryController@index')->name('inventory.index');
    Route::get('/inventory/search/{search}','InventoryController@search')->name('inventory.search');
    Route::get('/report/today','ReportController@today')->name('report.today');
    Route::get('/report/yesterday','ReportController@yesterday')->name('report.yesterday');
    Route::get('/report/week','ReportController@week')->name('report.week');
    Route::get('/report/month','ReportController@month')->name('report.month');
    Route::post('/report/filter','ReportController@filter')->name('report.filter');
    Route::get('/print/today','ReportController@printToday')->name('print.today');
    Route::get('/print/yesterday','ReportController@printYesterday')->name('print.yesterday');
    Route::get('/print/week','ReportController@printWeek')->name('print.week');
    Route::get('/print/month','ReportController@printMonth')->name('print.month');
    Route::get('/print/from/{from}/to/{to}','ReportController@printFiltered')->name('print.filter');
});

Auth::routes();