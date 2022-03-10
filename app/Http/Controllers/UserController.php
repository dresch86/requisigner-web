<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\User;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function search(Request $request) {
        try {
            $query_name = trim($request->input('query_name')) . '%';
            $users = User::select('id', 'name')
            ->where('name', 'like', $query_name)->get();

            $result = [];

            foreach ($users as $user) {
                $result[] = [$user->id, $user->name];
            }

            return response()->json([
                'code' => 200,
                'result' => $result
            ]);
        } catch (\Exception $e) {
            Log::critical($e);

            return response()->json([
                'code' => 500,
                'result' => 'A system error has occurred!'
            ]);
        }
    }
}
