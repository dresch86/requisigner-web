<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function settings_form(Request $request) {
        
    }

    public function settings_store(Request $request) {
        
    }

    public function group_store(Request $request) {
        
    }

    public function group_form(Request $request) {
        return view('superadmin.groups.create', ['menuItem' => 'admin_tools']);
    }

    public function user_delete(Request $request) {
        try {
            $json_body = $request->json()->all();
            $user_id = filter_var($json_body['user_id'], FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

            if (!is_null($user_id)) {
                $user = User::select('username')->where('id', '=', $user_id)->first();

                if (!is_null($user)) {
                    $username = $user->username;
                    User::where('id', '=', $user_id)->delete();

                    return response()->json([
                        'code' => 200,
                        'result' => ($username . ' was deleted!')
                    ]);
                } else {
                    return response()->json([
                        'code' => 404,
                        'result' => 'User no longer exists!'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 400,
                    'result' => 'Bad request!'
                ]);
            }
        } catch (\Exception $e) {
            Log::critical($e);

            return response()->json([
                'code' => 500,
                'result' => 'A system error occurred!'
            ]);
        }
    }

    public function user_store(Request $request) {
        try {
            $name = trim($request->input('human_name'));
            $username = trim($request->input('username'));
            $password = trim($request->input('password'));
            $office_fax = trim($request->input('office_fax'));
            $office_phone = trim($request->input('office_phone'));
            $office_location = trim($request->input('office_location'));
            $office_extension = trim($request->input('office_extension'));
            $email = filter_var(trim($request->input('email')), FILTER_VALIDATE_EMAIL, FILTER_NULL_ON_FAILURE);
            $group_id = filter_var(trim($request->input('user_group')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $superadmin = filter_var(trim($request->input('permission_level')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

            $errors = [];

            if (strlen($username) < 3) {
                $errors[] = 'Username must be at least 3 characters!';
            }

            if (strlen($password) < 6) {
                $errors[] = 'Password must be at least 6 characters!';
            } else {
                $password = Hash::make($password);
            }

            if (is_null($email)) {
                $errors[] = 'Invalid email address format!';
            }

            $user = User::select('id')
            ->where('username', '=', $username)
            ->orWhere('email', '=', $email)
            ->get();

            if (!$user->isEmpty()) {
                $errors[] = 'The username and/or email address already exists!';
            }

            if (count($errors) == 0) {
                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'group_id' => $group_id,
                    'office' => $office_location,
                    'phone' => $office_phone,
                    'fax' => $office_fax,
                    'extension' => $office_extension,
                    'superadmin' => $superadmin,
                    'suspended' => 0
                ]);
    
                $user->save();
    
                if ($user) {
                    return response()->json([
                        'code' => 200,
                        'result' => 'User created!'
                    ]);
                } else {
                    return response()->json([
                        'code' => 500,
                        'result' => 'An error occurred in adding the user!'
                    ]);
                }
            } else {
                return response()->json([
                    'code' => 400,
                    'result' => $errors
                ]);
            }
        } catch (\Exception $e) {
            Log::critical($e);

            return response()->json([
                'code' => 500,
                'result' => 'A system error has occurred!'
            ]);
        }
    }

    public function user_form(Request $request) {
        try {
            $groups = Group::select('id', 'name')->get();

            return view('superadmin.users.create', ['menuItem' => 'admin_tools', 'groups' => $groups]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function group(Request $request) {
        try {
            $user = User::where('id', '=', $request->id)->first();

            return view('superadmin.users.edit', ['menuItem' => 'admin_tools', 'user' => $user]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function groups(Request $request) {
        try {
            $groups = Group::select('id', 'name', 'parent_id', 'description')
            ->paginate(15);

            return view('superadmin.groups.list', ['menuItem' => 'admin_tools', 'groups' => $groups]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function user(Request $request) {
        try {
            $user = User::where('id', '=', $request->id)->first();

            return view('superadmin.users.edit', ['menuItem' => 'admin_tools', 'user' => $user]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function users(Request $request) {
        try {
            $users = User::select('id', 'name', 'username', 'group_id', 'office', 'phone', 'fax', 'extension', 'superadmin', 'suspended')
            ->paginate(15);

            return view('superadmin.users.list', ['menuItem' => 'admin_tools', 'users' => $users]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function tools(Request $request) {
        return view('superadmin.tools', ['menuItem' => 'admin_tools']);
    }
}
