<?php

namespace App\Http\Helpers;

use App\Http\Controllers\NotificationController;
use App\Models\User;
use GuzzleHttp\Exception\GuzzleException;

trait AuthHelper
{
    public function register_user($request)
    {
        $data = $request->validated();

        $user = self::create_user($data);

        $token = $user->createToken('UserToken')->accessToken;

        self::ok($user,$token);
    }

    public function login_user($request): void
    {
        $data = $request->validated();

        auth()->attempt($data) ? self::ok(auth()->user(), auth()->user()->createToken('UserToken')->accessToken) : self::unAuth();
    }

    public function update_user($request): void
    {
        $data = $request->validated();

        $user = $request->user();

        $user->update($data);

        self::ok($user);
    }

    public function logout_user($request): void
    {
        $request->user()->token()->revoke() ? self::ok() : self::unHandledError();
    }

    public function show_user_details($request): void
    {
        self::ok($request->user());
    }

    /**
     * @throws GuzzleException
     */
    public function send_order_notification_to_user($request, $user): void
    {
        if(isset($request['status']))
            (new NotificationController)->notify(
                'the order has updated',
                'the order new status is: ' . $request['status'],
                $user
            );
        if(isset($request['payment_status']))
            if($request['payment_status']) $paid = 'paid'; else $paid = 'not paid';
        (new NotificationController)->notify(
            'the order has updated',
            'the order set to: ' . $paid,
            $user
        );
    }

    public function edit_fcm_token($request): void
    {
        $user = $request->user();

        isset($request['fcm_token']) ? $user->device_key = $request['fcm_token'] : self::unHandledError();

        $user->save();

        self::ok();
    }
}
