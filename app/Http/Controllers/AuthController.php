<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $branding = $this->resolveBrandingByUsername($request->old('user', ''));

        return view('auth.login', [
            'loginLogo' => $branding['logo'],
            'loginCompanyName' => $branding['name'],
        ]);
    }

    public function companyLogo(Request $request)
    {
        $data = $request->validate([
            'user' => 'required|string|max:255',
        ]);

        return response()->json($this->resolveBrandingByUsername($data['user']));
    }

    private function resolveBrandingByUsername(?string $username): array
    {
        $default = [
            'logo' => asset('assets/images/logo.png'),
            'name' => 'CredyFácil Soluciones Financieras',
        ];

        $username = trim((string) $username);
        if ($username === '') {
            return $default;
        }

        $user = User::where('user', $username)->where('deleted', 0)->first();
        if (!$user) {
            return $default;
        }

        if ($user->hasRole('superadmin')) {
            return [
                'logo' => asset('assets/images/xinergia.png'),
                'name' => 'Xinergia SaaS',
            ];
        }

        $company = $user->company;
        if (!$company) {
            return $default;
        }

        return [
            'logo' => asset($company->logo ?: 'assets/images/logo.png'),
            'name' => $company->name,
        ];
    }

    public function check(Request $request){
        $credentials = $request->validate([
            'user' => 'required',
            'password' => 'required'
        ]);

        if(Auth::attempt($credentials)){
            // Si el usuario autenticado está inactivo (state == 1) no permitir acceso
            $user = Auth::user();
            if ($user && $user->state == 1) {
                Auth::logout();
                return back()->withErrors([
                    'user' => 'Usuario inactivo'
                ]);
            }

            if ($user->hasRole('superadmin')) {
                $request->session()->regenerate();
                return redirect()->route('superadmin.companies.index');
            }

            if ($user->company && $user->company->status != 1) {
                Auth::logout();
                return back()->withErrors([
                    'user' => 'Tu financiera se encuentra inactiva. Contacta al administrador.'
                ]);
            }

            $request->session()->regenerate();

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'user' => 'Usuario o contraseña incorrecta'
        ]);
    }

    public function logout(Request $request){
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
