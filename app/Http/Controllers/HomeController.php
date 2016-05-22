<?php namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input as Input;


class HomeController extends Controller{



    public function getIndex(){




        if(! \Auth::check() )
            return \Redirect::to('login');

        $user = \Auth::user();

        if( $user->hasRole('empresa'))
            return \Redirect::to('empresa');
        else
            return \Redirect::to('login');
    }



    public function showLogin(){

        if(\Auth::check() )
            \Auth::logout();

        return \View::make('pages/login');

    }

    public function doLogin(){


                // validate the info, create rules for the inputs
        $rules = array(
            'username'    => 'required',
            'password' => 'required|min:3'
        );

        // run the validation rules on the inputs from the form
        $validator = \Validator::make(Input::all(), $rules);

        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return \Redirect::to('login')
                ->withErrors($validator) // send back all errors to the login form
                ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        } else {

            // create our user data for the authentication
            $userdata = array(
                'username'     => Input::get('username'),
                'password'     => Input::get('password')
            );

            // attempt to do the login
            if (\Auth::attempt($userdata)) {

                // validation successful!
                // redirect them to the secure section or whatever
                // return Redirect::to('secure');
                // for now we'll just echo success (even though echoing in a controller is bad)
                return \Redirect::to('/');

            } else {

                // validation not successful, send back to form
                return \Redirect::to('login');

            }

        }

    }
}


?>
