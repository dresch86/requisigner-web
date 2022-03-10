<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Settings;
use App\Models\Signature;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use Mews\Purifier\Facades\Purifier;

class AdminController extends Controller
{
    public function settings_form(Request $request) {
        try {
            $settings = Settings::select('id', 'setting', 'value')->get();
            $settingObj = new \stdClass();

            foreach ($settings as $setting) {
                $settingObj->{$setting->setting} = [$setting->id, $setting->value];
            }

            return view('superadmin.settings', ['menuItem' => 'admin_tools', 'settings' => $settingObj]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function settings_store(Request $request) {
        
    }

    public function group_update(Request $request) {
        try {
            $errors = [];

            $group_name = trim($request->input('group_name'));
            $group_description = trim($request->input('group_description'));
            $group_parent_id = filter_var(trim($request->input('group_parent')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

            if (strlen($group_name) < 3) {
                $errors[] = 'Group name must be at least 3 characters!';
            }

            if (is_null($group_parent_id)) {
                $errors[] = 'Group parent id must be numeric!';
            } else {
                if ($group_parent_id == 0) {
                    $group_parent_id = null;
                }
            }

            $group_check = Group::select('id')
            ->where('id', '!=', $request->id)
            ->where('name', '=', $group_name)
            ->get();

            if (!$group_check->isEmpty()) {
                $errors[] = 'Group already exists!';
            }

            if ($group_parent_id !== null) {
                $parent_check = Group::where('id', '=', $group_parent_id)->get();

                if ($parent_check->isEmpty()) {
                    $errors[] = 'Group parent no longer exists!';
                }
            }

            if (count($errors) == 0) {
                if (strlen($group_description) > 0) {
                    // Safeguards against XSS
                    Purifier::clean($group_description);
                }

                $result = Group::where('id', '=', $request->id)
                ->update([
                    'name' => $group_name,
                    'parent_id' => $group_parent_id,
                    'description' => $group_description
                ]);
    
                if ($result == 1) {
                    return response()->json([
                        'code' => 200,
                        'result' => 'Group updated!'
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

    public function group_store(Request $request) {
        try {
            $errors = [];

            $group_name = trim($request->input('group_name'));
            $group_description = trim($request->input('group_description'));
            $group_parent_id = filter_var(trim($request->input('group_parent')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

            if (strlen($group_name) < 3) {
                $errors[] = 'Group name must be at least 3 characters!';
            }

            if (is_null($group_parent_id)) {
                $errors[] = 'Group parent id must be numeric!';
            }

            $group_check = Group::where('name', '=', $group_name)->get();

            if (!$group_check->isEmpty()) {
                $errors[] = 'Group already exists!';
            }

            $parent_check = Group::where('id', '=', $group_parent_id)->get();

            if ($parent_check->isEmpty()) {
                $errors[] = 'Group parent no longer exists!';
            }

            if (count($errors) == 0) {
                if (strlen($group_description) > 0) {
                    // Safeguards against XSS
                    Purifier::clean($group_description);
                }

                $group = Group::create([
                    'name' => $group_name,
                    'parent_id' => $group_parent_id,
                    'manager_id' => auth()->user()->id,
                    'description' => $group_description
                ]);
    
                $group->save();
    
                if ($group) {
                    return response()->json([
                        'code' => 200,
                        'result' => 'Group created!'
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
                'result' => 'A system error occurred!'
            ]);
        }
    }

    public function group_form(Request $request) {
        try {
            $groups = Group::select('id', 'name')->get();
            return view('superadmin.groups.create', ['menuItem' => 'admin_tools', 'groups' => $groups]);
        } catch (\Exception $e) {
            Log::critical($e);
        }
    }

    public function group(Request $request) {
        try {
            $groups = Group::select('id', 'name')->get();
            $group_modifying = Group::where('id', '=', $request->id)->first();

            return view('superadmin.groups.edit', ['menuItem' => 'admin_tools', 'group_modifying' => $group_modifying, 'groups' => $groups]);
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
                DB::beginTransaction();

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
                    $sig_directory = 'private/signatures/' . $user->id;
                    Storage::disk('local')->makeDirectory($sig_directory);

                    $signature = Signature::create([
                        'user_id' => $user->id
                    ]);           
                    $signature->save();

                    DB::commit();

                    return response()->json([
                        'code' => 200,
                        'result' => 'User created!'
                    ]);
                } else {
                    DB::rollBack();

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

    public function user_update(Request $request) {
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
            $suspended = filter_var(trim($request->input('user_suspended')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);
            $superadmin = filter_var(trim($request->input('permission_level')), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE);

            $errors = [];

            $update_fields = [
                'name' => $name,
                'username' => $username,
                'email' => $email,
                'group_id' => $group_id,
                'office' => $office_location,
                'phone' => $office_phone,
                'extension' => $office_extension,
                'fax' => $office_fax,
                'superadmin' => $superadmin,
                'suspended' => $suspended
            ];

            if (empty($name)) {
                $errors[] = 'You must enter a name for the user!';
            }

            if (strlen($username) < 3) {
                $errors[] = 'Username must be at least 3 characters!';
            }

            if (strlen($password) > 0) {
                if ($password == $password_confirm) {
                    $update_fields['password'] = Hash::make($password);
                } else {
                    $errors[] = 'Passwords mismatched!';
                }
            }

            if (is_null($email)) {
                $errors[] = 'Invalid email address format!';
            }

            $user = User::select('id')
            ->where('id', '!=', $request->id)
            ->where(function($query) use ($username, $email) {
                $query->where('username', '=', $username);
                $query->orWhere('email', '=', $email);
            })
            ->get();

            if (!$user->isEmpty()) {
                $errors[] = 'The username and/or email address already exists!';
            }

            if (count($errors) == 0) {
                $result = User::where('id', '=', $request->id)
                ->update($update_fields);

                if ($result == 1) {
                    return response()->json([
                        'code' => 200,
                        'result' => 'User updated!'
                    ]);   
                } else {
                    return response()->json([
                        'code' => 500,
                        'result' => 'Failed to update user!'
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

    public function user(Request $request) {
        try {
            $groups = Group::select('id', 'name')->get();
            $user = User::where('id', '=', $request->id)->first();

            return view('superadmin.users.edit', ['menuItem' => 'admin_tools', 'user' => $user, 'groups' => $groups]);
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
