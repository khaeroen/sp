<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Article;
use App\Publication;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/tesis', 'TesisController@index');

Route::resource('articles', 'ArticlesController');

Route::resource('publications', 'PublicationsController');

Route::post('publish',function(Request $request) {

	$id = $request->id;

	$article= Article::findOrFail($id);

		if($article->publish == 1)
        {
            DB::table('articles')->where('id',$id)->update(['publish' => 0]);
        }
        else 
        {
            DB::table('articles')->where('id',$id)->update(['publish' => 1]);
        }

        return redirect()->back();
});

Route::post('publish-publication',function(Request $request) {

	$id = $request->id;

	$article= Publication::findOrFail($id);

		if($article->publish == 1)
        {
            DB::table('publications')->where('id',$id)->update(['publish' => 0]);
        }
        else 
        {
            DB::table('publications')->where('id',$id)->update(['publish' => 1]);
        }

        return redirect()->back();
});
