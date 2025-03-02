<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserStatisticResource;
use App\Models\Logs;
use App\Models\ProfileImage;
use App\Models\Role;
use App\Models\RoleAccess;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Pusher\Pusher;

class UserController extends Controller
{

    public function index()
    {
        return UserResource::collection(User::all());
    }


    public function statisticShow()
    {
        return UserStatisticResource::collection(User::orderBy('id', 'desc')->get());
    }

    public function uploadAvatar(Request $request)
    {
        Log::info('Image Load:', $request->all());

        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $user = auth()->guard('sanctum')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if ($file = $request->file('file')) {
            $imagePath = Storage::disk('public')->put('avatars', $file);
            $publicUrl = url(Storage::url($imagePath));

            $avatar = ProfileImage::where('user_id', $user->id)->first();
            if ($avatar != null) {
                $avatar->update(['path' => $publicUrl]);

                Logs::create([
                    'user_id' => $user->id,
                    'type' => 'update_profileImage',
                    'data' => json_encode($avatar),
                ]);
            } else {
                $avatar = ProfileImage::create([
                    'user_id' => $user->id,
                    'path' => $publicUrl
                ]);

                Logs::create([
                    'user_id' => $user->id,
                    'type' => 'create_profileImage',
                    'data' => json_encode($avatar),
                ]);
            }
        }

        return response()->json(['path' => $publicUrl]);
    }

    public function updateSelected(UserUpdateRequest $request, string $id)
    {
        Log::info('Update selected user request data:', $request->all());

        $requestData = $request->toArray()["user_data"]["data"];
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user->update(array_filter([
            'name' => $requestData['name'] ?? $user->name,
            'email' => $requestData['email'] ?? $user->email
        ]));

        $roleNow = RoleAccess::all()->firstWhere("user_id", '=', $user->id);

        if ($roleNow->role_id != $requestData['role']['id']) {
            $roleNow->update(array_filter([
                'role_id' => $requestData['role']['id']
            ]));
        }

        Logs::create([
            'user_id' => auth()->guard('sanctum')->user()->id,
            'type' => 'update_user',
            'data' => json_encode($user),
        ]);

        return response()->json([
            'user' => UserResource::make($user),
        ]);
    }


    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);


        $user = User::all()->firstWhere("email", '=', $validated['email']);

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        Logs::create([
            'user_id' => $user->id,
            'type' => 'login_user',
            'data' => json_encode($user),
        ]);

        return response()->json([
            'user' => UserResource::make($user),
            'token' => $token
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users',
            'login' => 'required|string|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $validated['login'],
            'email' => $validated['email'],
            'login' => $validated['login'],
            'password' => bcrypt($validated['password']),
            'rank_id' => 3
        ]);

        RoleAccess::create([
            'user_id' => $user->id,
            'role_id' => 1
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Logs::create([
            'user_id' => $user->id,
            'type' => 'create_user',
            'data' => json_encode($user),
        ]);

        return response()->json([
            'user' => UserResource::make($user),
            'token' => $token
        ]);
    }

    public function show(string $id)
    {
        return UserResource::make(User::find($id));
    }


    public function profile(string $id)
    {
        if ($id) {
            return UserResource::make(User::find($id));
        } else
            return UserResource::make(User::find(auth()->guard('sanctum')->id()));
    }

    public function update(UserUpdateRequest $request)
    {
        Log::info('Update request data:', $request->all());

        $request = $request->toArray()["user_data"];

        $user = auth()->guard('sanctum')->user();
        if ($user == null) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user->update(array_filter([
            'name' => $request['name'] ?? $user->name,
            'email' => $request['email'] ?? $user->email
        ]));

        $avatar = ProfileImage::where('user_id', $user->id)->first();

        if ($avatar != null && $request['avatar'] != null) {
            $avatar->update(['path' => $request['avatar']['path']]);

            Logs::create([
                'user_id' => auth()->guard('sanctum')->user()->id,
                'type' => 'update_profileImage',
                'data' => json_encode($avatar),
            ]);

        } elseif ($request['avatar'] != null) {
            $avatar = ProfileImage::create([
                'user_id' => $user->id,
                'path' => $request['avatar']['path']
            ]);

            Logs::create([
                'user_id' => auth()->guard('sanctum')->user()->id,
                'type' => 'create_profileImage',
                'data' => json_encode($avatar),
            ]);
        }

        Logs::create([
            'user_id' => auth()->guard('sanctum')->user()->id,
            'type' => 'update_user',
            'data' => json_encode($user),
        ]);

        return response()->json([
            'user' => UserResource::make($user),
        ]);
    }

    public function destroy(string $id)
    {
        $SelectedUser = User::find($id);

        if (!$SelectedUser) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $user = auth()->guard('sanctum')->user();

        $role = Role::find(RoleAccess::where('user_id', $user->id)->first()->role_id);

        if ($user && $role->priority > 20) {

            Logs::create([
                'user_id' => auth()->guard('sanctum')->user()->id,
                'type' => 'delete_user',
                'data' => json_encode($user),
            ]);

            $SelectedUser->delete();
        }

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function AdminCheck(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $role = Role::find(RoleAccess::where('user_id', $user->id)->first()->role_id);

        if ($user && $role->priority > 20) { // Assuming rank_id 1 is for admin
            return response()->json(['status' => '200'], 200);
        }

        return response()->json(['status' => '403'], 403);
    }
}
