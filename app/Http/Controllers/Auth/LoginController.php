<?php namespace  App\Http\Controllers\Auth;

use App\Models\User;
use Main\Core\Auth;
use Main\Core\Controller;
use Rakit\Validation\ErrorBag;


class LoginController extends Controller
{

    public function loginView()
    {
        if(auth()->check())
            return redirect('/user');
        return $this->render('auth.login');
    }

    public function login()
    {

        if(auth()->check())
        return redirect('/user');

        $validation = $this->validate(request()->all(),[
            "email" => "required|email|max:255|exist:users,email",
            "password" => "required|min:5|max:255",
        ],[
            "email:email" => "Email Is Wrong",
            "email:required" => "Email Cant Be Empty",
            "email:max" => "Email Cant Be Bigger Than 255",
            "email:exist" => "Email Is Not exists",
            "password:required" => "Password Cant Be Empty",
            "password:min" => "Password Cant Be Lower Than 5",
            "password:max" => "Confirm Password Cant Be Bigger Than 255",
        ]);

        if ($validation->fails()) {
            return redirect("/auth/login");
        }

        $data = $validation->getValidatedData();

        $user = (new User)->find($data['email'], 'email');

        if(!password_verify($data['password'], $user->password))
        {
            $errors = new ErrorBag();
            $errors->add('password','check-password',"The Information Does not match");
            return redirect("/auth/login")
                    ->withErrors($errors)
                    ->withInputs();
        }

        auth()->login($user->id);

        return redirect('/user');

    }

    public function logout()
    {
        if(!auth()->check())
            return redirect("/auth/login");
        
        auth()->logout();

        return redirect("/auth/login");
    }

}

