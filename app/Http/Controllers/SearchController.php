<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use TeamTNT\TNTSearch\TNTSearch;

use Config;

class SearchController extends Controller
{


    public function index(Request $request){

        $tnt = new TNTSearch;

        // Get current dbconfig
        $dbConfig = array_merge(Config::get('database.connections.' . Config::get('database.default')));

        $dbConfig['storage'] = storage_path('app/');

        $tnt->loadConfig($dbConfig);

        $tnt->selectIndex("name.index");

        $tnt->asYouType = true;

        $results = $tnt->search($request->get('query'), 10);

        return $results;
    }
}
