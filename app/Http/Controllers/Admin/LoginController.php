<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    
    //

    use AuthenticatesUsers;

    protected $redirectTo = '/admin';

    public function __construct() {
        $this->middleware('guest:admin')->except('logout');
    }


    public function loginForm() {
        return view('admin.auth.login');
    }

    public function index()
    {
        return view('admin.dashboard.index');
    }
    
    public function login(Request $request) {

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if(Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $request->get('remember'))) {
            return redirect()->intended(route('admin.dashboard'));
        }
            return back()->withInput($request->only('email', 'remember'));
    }


    protected function attemptLogin(Request $request){
        $customerAttempt = Auth::guard('employee')->attempt(
            $this->credentials($request), $request->has('remember')
        );
        if(!$customerAttempt){
            return Auth::guard()->attempt(
                $this->credentials($request), $request->has('remember')
            );
        } 
        return $customerAttempt;    
        
    }

    public function logout(Request $request) {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }
}
