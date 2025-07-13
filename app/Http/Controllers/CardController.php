<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Card;

class CardController extends Controller
{
    public function index() {
        return view('admin.cards.index', [
            'cards' => Card::all()
        ]);
    }

    public function create() {
        return view('admin.cards.create');
    }

    public function store() {
        $card = new Card;
        $card->title = request('card_title');
        $card->content = request('card_content');
        $card->date = request('card_date');
        $card->explanation = request('card_explanation');
        $card->answer = request('card_answer');
        $card->save();

        return redirect("/cards", 302);
    }

    public function redirect_to_edit(Request $request) {
        $selected_card_id = $request->card_id;
        return redirect("/cards/$selected_card_id/edit", 302);
    }

    public function edit(Card $card) {
        return view('admin.cards.edit', [
            'card' => $card
        ]);
    }

    public function update(Card $card) {
        //Update card
        $card->title = request('card_title');
        $card->content = request('card_content');
        $card->date = request('card_date');
        $card->explanation = request('card_explanation');
        $card->answer = request('card_answer');
        $card->save();;

        return redirect("/cards/$card->id/edit", 302);
    }

    public function destroy(Card $card) {
        $card->delete();
        return redirect("/cards", 302);
    }
}
