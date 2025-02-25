<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;

class CardController extends Controller
{
    public function index() {
        return view("/dashboard", ['cards' => Card::all()]);
    }
}
