<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\ResponseController;
use App\Http\Requests\SetPasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class ProfileController extends ResponseController
{
    /**
     * @param SetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function setPassword(Request $request) {
        $user = $request->user();

        $currentPassword = $request->currentPassword;
        $newPassword = $request->newPassword;

        if (Hash::check($newPassword, $user->password)) {
            return $this->sendError('You had such a password!', 422);
        }

        if (!Hash::check($currentPassword, $user->password)) {
            return $this->sendError('Invalid current password', 422);
        }

        $hashedNewPassword = Hash::make($newPassword);
        $user->password = $hashedNewPassword;
        $user->save();

        return $this->sendResponse([
            'user' => new UserResource($user),
            'message' => 'Password changed successfully!'
        ]);
    }

    public function setUserData(Request $request)
    {
        $user = auth()->user();

        $fields = collect($request->all())->keyBy(function ($value, $key) {
            return snake_case($key);
        })->all();

        $user->fill($fields)->save();
        // 'first_name' => $request->firstName,
        // 'last_name' => $request->lastName,
        // 'gender' => $request->gender,
        // 'birthday' => $request->birthday,
        // 'timezone' => $request->timezone,
        // 'country' => $request->country

        return new UserResource($user);
    }


}
