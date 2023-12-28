<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Envio;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ColetaController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function getlistItens($id)
    {
        $envios = Envio::where("coleta_id",$id)->paginate();
        //return view('layouts.dashboard',compact('envios'));

        // $coletas = DB::table('coletas')->join('envios','coletas.id','=','envios.coleta_id')->select('coletas.id',DB::raw('sum(envios.valor_total) as total'),DB::raw('sum(envios.valor_desconto) as desconto'))->where("coletas.id","=",$id)->groupBy("coletas.id")->get();
        // print_r($envios);exit;
        return response()->json(['html' =>view('layouts.coleta.detalhesColeta',compact('envios'))->render()]);

    }

    public function teste()
    {
        return true;
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
