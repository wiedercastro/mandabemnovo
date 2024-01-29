<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Envio;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): View
    {
        $request->authenticate();

        $request->session()->regenerate();
        // $ip_do_usuario = $_SERVER['REMOTE_ADDR'];

        // $envios = Envio::where("user_id",$request->user()->id)->paginate();
        $envios = DB::table('coletas')->join('envios','coletas.id','=','envios.coleta_id')->select('coletas.id',DB::raw('Count(envios.id) as qte'),DB::raw('sum(envios.valor_total) as total'),DB::raw('sum(envios.valor_desconto) as desconto'),'coletas.type')->where("coletas.user_id","=",5)->groupBy("coletas.id")->paginate();
        // return redirect()->intended(RouteServiceProvider::HOME);
        // print_r($envios);exit;
        return view('layouts.etiquetas', compact("envios"));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
