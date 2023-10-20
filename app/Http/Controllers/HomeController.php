<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (!auth()->check()) return redirect("/");
        if (auth()->user()->role == 'admin') return redirect("/admin");
        return view('main.welcome');
    }

    public function login()
    {
        $message = "Invalid login combinations, application no or password";
        if (!request()->jambNo || !request()->password) {
            return redirect("/")->with(["errorMessage" => $message]);
        }

        $jambNo = request()->jambNo;
        $password = request()->password;
        $school = school();
        if (!$school) {
            return redirect("/")->with(["errorMessage" => "Invalid school"]);
        }
        $user = User::where("jambNo", $jambNo)->where("school_id", $school->id)->first();
        if ($user) {
            // validate password 
            if (Hash::check($password, $user->password)) {
                // check user type
                if (Hash::check($password, $user->password)) {
                    Auth::login($user);
                    request()->session()->regenerate();
                    if ($user->role == 'admin') {
                        return redirect("/admin");
                    }
                    return redirect("/welcome");
                } else {
                    return redirect("/")->with(["errorMessage" => $message]);
                }
                // $credentials = ["email" => $user->email, "password" => $password];
                // if (Auth::attempt($credentials)) {
                //     request()->session()->regenerate();
                //     if ($user->role == 'admin') {
                //         return redirect("/admin");
                //     }
                //     return redirect("/welcome");
                // } else {
                //     dd("Helloworld");
                // }
            } else {
                return redirect("/")->with(["errorMessage" => $message]);
            }
        } else {
            return redirect("/")->with(["errorMessage" => $message]);
        }
    }

    public function startquiz()
    {
        $categories = Category::where("school_id", school()->id)->get();
        return view("main.start", ["categories" => $categories]);
    }
}
