<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BulkemailController extends Controller
{
    public function create(){
        return view('admin-views.bulk_email');
    }
}
