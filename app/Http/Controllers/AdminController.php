<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exam;
use App\Models\Question;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect('/');
        }
        if (auth()->user()->role == 'student') return redirect("/welcome");

        $Questions = Question::where("school_id", school()->id)->get();
        $subjects = Category::where("school_id", school()->id)->get();
        $students = User::where("school_id", school()->id)->where("role", "student")->get();
        $admins = User::where("school_id", school()->id)->where("role", "admin")->get();
        $exams = Exam::where("school_id", school()->id)->get();
        return view(
            "admin.index",
            [
                "questions" => $Questions,
                "subjects" => $subjects,
                "students" => $students,
                "exams" => $exams,
                "admins" => $admins
            ]
        );
    }

    public function students()
    {
        if (!auth()->check()) {
            return redirect('/');
        }
        if (auth()->user()->role == 'student') return redirect("/welcome");
        $users = User::all()->where("role", "student")->where("school_id", school()->id);
        return view("admin.users", ["students" => $users]);
    }

    public function Addstudent()
    {
        if (!auth()->check()) {
            return redirect('/');
        }
        if (auth()->user()->role == 'student') return redirect("/welcome");
        return view("admin.addstudent");
    }
    public function AddstudentStore(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/');
        }
        if (auth()->user()->role == 'student') return redirect("/welcome");

        $this->validate($request, [
            "name" => ["required"],
            "email" => ["required"],
            "jambNo" => ["required"],
            "password" => ["required"],
            "role" => [""],
        ]);
        $isEmail = User::where("email", $request->email)->where("school_id", school()->id)->first();
        $isJamb = User::where("email", $request->jambNo)->where("school_id", school()->id)->first();
        if ($isEmail) {
            return redirect("/admin/students/add")->with("message", "Student email already exist");
        }

        if ($isJamb) {
            return redirect("/admin/students/add")->with("message", "Student record already exist, check student number");
        }

        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "jambNo" => $request->jambNo,
            "password" => Hash::make($request->password),
            "role" => $request->role,
            "school_id" => school()->id
        ];
        $user = User::create($data);
        if ($user) {
            return redirect("/admin/students")->with("message", "Registration success");
        } else {
            return redirect("/admin/students/add")->with("message", "Registration Failed");
        }
        return view("admin.addstudent");
    }

    public function update(Request $request, $id)
    {
        $subject = Category::where("id", $id)->first();
        $subject->isList = $request->status == 'Unlist' ? false : true;

        $subject->save();
        return redirect()->back();
    }
}
