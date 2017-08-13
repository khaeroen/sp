<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use DB;

class TesisController extends Controller
{

    public function index()
    {
    	// get data entry tesis
    	/*
		1. check wp_cf_form_entries, select form_id for tesis : CF598c0f8c1da4c, get entry id
		2. foreach entry id --> retrieve items
    	*/
    	$rows = [];

    	$i = 0;

    	$n = 1;

    	$getEntryId = DB::table('wp_cf_form_entries')->where('form_id', 'CF598c0f8c1da4c')->lists('id');

    	foreach ($getEntryId as $entryId) {
    		$rows[$i] = DB::table('wp_cf_form_entry_values')->where('entry_id', $entryId)->lists('value','slug');

    		$i++; 
    	}

        $searchText = null;

        $searchCategory = null;

        

            // search title thesis or author
        if(isset($_GET['search']))
        {
            $searchText = $_GET['search'];

            $searchCategory = $_GET['searchBy'];

            unset($rows);

            $rows = array(); //reset rows value

            if ($_GET['searchBy'] == 'title') {

                // select id which entry id from the filter result

                $searchEntryId = DB::table('wp_cf_form_entry_values')->where('slug', 'title_thesis')
                    ->where('value','LIKE','%'.$_GET['search'].'%')->lists('entry_id');

                foreach ($searchEntryId as $entryId) {
                    $rows[$i] = DB::table('wp_cf_form_entry_values')->where('entry_id', $entryId)
                    ->lists('value','slug');

                    $i++; 
                }

            }

            // search author
            if ($_GET['searchBy'] == 'author') {

                $searchEntryId = DB::table('wp_cf_form_entry_values')->where('slug', 'full_name')
                    ->where('value','LIKE','%'.$_GET['search'].'%')->lists('entry_id');

                foreach ($searchEntryId as $entryId) {
                    $rows[$i] = DB::table('wp_cf_form_entry_values')->where('entry_id', $entryId)
                    ->lists('value','slug');

                    $i++; 
                }
            }
        }


    	return view('welcome',compact('rows','n','searchText','searchCategory'));
    }
}
