<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "email" => [
                "required",
                "string",
                "lowercase",
                "email",
                "max:255",
                "unique:" . User::class,
            ],
            "password" => ["required", "confirmed", Rules\Password::defaults()],
            "profile_picture" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);
    
    
        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        if($request->hasFile('profile_picture')){
            $file = $request->file('profile_picture');

            $binaryData = file_get_contents($file);
            if (!$binaryData) {
        throw new \Exception('Failed to read binary data from profile picture.');
    }

            $user->profile_picture = $binaryData;

            $user->save();
        }

    

       

        event(new Registered($user));

        Auth::login($user);
       if ($user->profile_picture) {
            $user->profile_picture = base64_encode($user->profile_picture);
        }

     
        return response()->json([
    'message' => 'User is successfully registered',
    'user' => $user, 
]);
    }
}
