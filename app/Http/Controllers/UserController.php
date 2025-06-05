<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserStatisticResource;
use App\Models\Logs;
use App\Models\ProfileImage;
use App\Models\Rank;
use App\Models\rank_progresses;
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

    private function deleteOldFileFromPublicStorage(?string $url): void
    {
        if ($url) {
            $relativePath = str_replace('/storage/', '', parse_url($url, PHP_URL_PATH));
            Storage::disk('public')->delete($relativePath);
        }
    }


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
                $this->deleteOldFileFromPublicStorage($avatar->path);
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

    public function uploadBanner(Request $request)
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
            $imagePath = Storage::disk('public')->put('banners', $file);
            $publicUrl = url(Storage::url($imagePath));

            $avatar = ProfileImage::where('user_id', $user->id)->first();
            if ($avatar != null) {
                $this->deleteOldFileFromPublicStorage($avatar->path);
                $avatar->update(['banner' => $publicUrl]);

                Logs::create([
                    'user_id' => $user->id,
                    'type' => 'update_profileImage',
                    'data' => json_encode($avatar),
                ]);
            } else {
                $avatar = ProfileImage::create([
                    'user_id' => $user->id,
                    'banner' => $publicUrl
                ]);

                Logs::create([
                    'user_id' => $user->id,
                    'type' => 'create_profileImage',
                    'data' => json_encode($avatar),
                ]);
            }
        }

        return response()->json(['banner' => $publicUrl]);
    }

    public function updateSelected(UserUpdateRequest $request, string $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        $user->update(array_filter([
            'name' => $request['name'] ?? $user->name,
            'email' => $request['email'] ?? $user->email
        ]));

        if ($request->has('progress')) {
            $progress = rank_progresses::where('user_id', $id);
            $progress->update(array_filter([
                'current_xp' => $request->progress,
            ]));
        }

        $roleNow = RoleAccess::all()->firstWhere("user_id", '=', $user->id);

        if ($roleNow->role_id != $request['role']['id']) {
            $roleNow->update(array_filter([
                'role_id' => $request['role']['id']
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

        $GuestRank = Rank::where('priority', 0)->first();

        $user = User::create([
            'name' => $validated['login'],
            'email' => $validated['email'],
            'login' => $validated['login'],
            'password' => bcrypt($validated['password']),
            'rank_id' => $GuestRank->id,
        ]);

        $userRole = Role::where('name', 'user')->first();

        RoleAccess::create([
            'user_id' => $user->id,
            'role_id' => $userRole->id,
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

    public function updateBio(Request $request)
    {
        $user = auth()->guard('sanctum')->user();

        $validated = $request->validate([
            'bio' => 'required|string|max:255'
        ]);

        $user->update(array_filter([
            'bio' => $validated['bio']
        ]));
        return response()->json(['user' => UserResource::make($user)]);
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

    public function nameUpdate (Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:30',
        ]);

        $user = auth()->guard('sanctum')->user();

        $user->update(array_filter([
            'name' => $validated['name'] ?? $user->name,
        ]));

        return response()->json(['status' => 'success'], 200);
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

        if (!$user) {
            return response()->json(['isAdmin' => false, 'isModerator' => false, 'isUser' => false], 401);
        }

        $role = RoleAccess::where('user_id', $user->id)
            ->join('roles', 'role_accesses.role_id', '=', 'roles.id')
            ->orderByDesc('roles.priority')
            ->select('roles.*')
            ->first();

        return response()->json([
            'isAdmin' => $role && $role->isAdmin,
            'isModerator' => $role && ($role->isModerator || $role->isAdmin),
        ]);
    }
}
