<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Card;

class checkingController extends Controller
{
    //
    public function check1()
    {
        return <<< EOF
                <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Document</title>
        </head>
        <body>
        Larvel dispaly check1 func
        </body>
        </html>
        EOF;
    }

    public function check2(Request $request, Response $response)
    {
        $data = [
            'url' => $request->url(),
            'fullurl' => $request->fullurl(),
            'path' => $request->path(),
        ];
        return view('test.other', $data);
    }

    public function getCardData() {
        $card = new Card;

        $results = $card->where('id', 1)->get();
        //$results = $card::all();
        return view('test.card', ['results' => $results]);
        //$arr = ['Snome1', 'Snome2', 'Snome3'];
        //return view('sample', compact('value', 'arr'));
      }
}
