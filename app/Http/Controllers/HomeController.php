<?php

namespace App\Http\Controllers;

use App\Animal;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::user()->accreditation == 1) {
            $animals = User::find(Auth::user()->id)->animals;
            $p = count($animals);
            if ($p == 0) {
                return view('home');
            } else {
                return view('home', compact('animals'));
            }
        } else {
            $dataMessage = User::find(Auth::user()->id)->messages;
            return view('admin', compact('dataMessage'));
        }
    }

    public function post(Request $request)
    {
        // dd($request->input());
        if (isset($request['addAnimal'])) {
            $validator = Validator::make(
                $request->all(),
                [
                    'animalName' => 'required|min:3',
                    'animalType' => 'required',
                    'animalRace' => 'required',
                ]
            );

            if ($validator->fails()) {
                return redirect('home')->withErrors($validator)->withInput();
            } else {
                $animal = new Animal();
                $animal->name = $request->input('animalName');
                $animal->type = $request->input('animalType');
                $animal->race = $request->input('animalRace');

                if ($request->input('avatar') != null) {
                    $animal->avatar = $request->input('avatar');
                }
                $animal->user_id = Auth::user()->id;
                $animal->save();
                return redirect('home')->with(['status' => 'Animal bien enregistré']);
            }
        } elseif (isset($request['sendMessage'])) {
            $validator = Validator::make(
                $request->all(),
                [
                    'messageSujet' => 'required',
                    'messageTexte' => 'required',
                ]
            );
            if ($validator->fails()) {
                return redirect('home')->withErrors($validator)->withInput();
            } else {
                $destinataire = User::where('name', 'admin')->first();
                $message = new Message();
                $message->sujet = $request->input('messageSujet');
                $message->contenu = $request->input('messageTexte');
                $message->expediteur = Auth::user()->id;
                $message->user_id = $destinataire->id;
                $message->save();
                return redirect('home')->with(['status' => 'Message envoyé']);
            }
        }
    }
}
