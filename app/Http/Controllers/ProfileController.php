<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function update(Request $request) {
        try {
            $errors = [];

            $name = strip_tags(trim($request->input('human_name')));
            $password = trim($request->input('password'));
            $password_confirm = trim($request->input('password_confirm'));
            $email = filter_var(trim($request->input('email')), FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);

            $office = strip_tags(trim($request->input('office_location')));
            $office_fax = strip_tags(trim($request->input('office_fax')));
            $office_phone = strip_tags(trim($request->input('office_phone')));
            $office_extension = strip_tags(trim($request->input('office_extension')));

            $update_fields = [
                'name' => $name,
                'email' => $email,
                'office' => $office,
                'phone' => $office_phone,
                'extension' => $office_extension,
                'fax' => $office_fax
            ];

            if (is_null($email)) {
                $errors[] = 'Invalid email address!';
            }

            if (empty($name)) {
                $errors[] = 'You must enter your name!';
            }

            if (strlen($password) > 0) {
                if ($password == $password_confirm) {
                    $update_fields['password'] = Hash::make($password);
                } else {
                    $errors[] = 'Passwords mismatched!';
                }
            }

            if (count($errors) > 0) {
                return response()->json([
                    'code' => 400,
                    'result' => $errors
                ]);
            } else {
                $result = User::where('id', '=', auth()->user()->id)
                ->update($update_fields);

                if ($result == 1) {
                    return response()->json([
                        'code' => 200,
                        'result' => 'Profile updated!'
                    ]);   
                } else {
                    return response()->json([
                        'code' => 500,
                        'result' => 'Failed to update profile!'
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::critical($e);

            return response()->json([
                'code' => 500,
                'result' => 'A system error has occurred!'
            ]);
        }
    }

    public function profile(Request $request) {
        try {
            $user = auth()->user();
            return view('profile', ['menuItem' => 'profile', 'user' => $user]);
        } catch (\Exception $e) {
            Log::critical($e);

            return response()->json([
                'code' => 500,
                'result' => 'A system error has occurred!'
            ]);
        }
    }
}
