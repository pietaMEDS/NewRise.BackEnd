<?php

namespace App\Http\Controllers;

use App\Models\Version;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VersionController extends Controller
{
    public function show($version): JsonResponse
    {
        $user = auth()->guard('sanctum')->user();
        $currentVersion = Version::where('name', $version)->first();

        if (!$currentVersion) {
            return response()->json([
                'status' => 'error',
                'ERR_CODE' => 'NOT_FOUND',
                'message' => 'Version does not exist'
            ], 500);
        }

        // Получаем ID ролей пользователя
        $userRoles = $user ? $user->roles()->pluck('id')->toArray() : [];

        // Делаем так, чтобы `access_group` тоже был массивом
        $accessGroups = is_array($currentVersion->access_group) ?
            $currentVersion->access_group :
            [$currentVersion->access_group];

        $hasAccess = !empty($accessGroups) && !empty($userRoles) && array_intersect($userRoles, $accessGroups);

        if ($currentVersion->is_supported) {
            if (!empty($accessGroups)) {
                if ($hasAccess) {
                    return response()->json([
                        'status' => 'ok',
                        'message' => 'You are using supported dev version',
                        'version_info' => $currentVersion,
                    ]);
                }
                return response()->json([
                    'status' => 'error',
                    'ERR_CODE' => 'NO_ACCESS',
                    'message' => 'You don’t have access to this version',
                    'version_info' => $currentVersion,
                ], 401);
            }

            return response()->json([
                'status' => 'ok',
                'message' => 'You are using supported version',
                'version_info' => $currentVersion,
            ]);
        }

        // Версия не поддерживается
        if (!empty($accessGroups)) {
            if ($hasAccess) {
                return response()->json([
                    'status' => 'update_required',
                    'message' => 'You are using an unsupported archive version, update required',
                    'version_info' => $currentVersion,
                ], 426);
            }
            return response()->json([
                'status' => 'error',
                'ERR_CODE' => 'NO_ACCESS',
                'message' => 'You don’t have access to this version',
                'version_info' => $currentVersion,
            ], 401);
        }

        return response()->json([
            'status' => 'update_required',
            'message' => 'You are using an unsupported archive version, update required',
            'version_info' => $currentVersion,
        ], 426);
    }

}
