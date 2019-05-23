<?php

namespace Tests\Concerns;

use Tests\TestCase as BaseTestCase;

use App\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

trait InteractsWithSessionAPI
{
    private static $user = null;
    private static $session = null;
    private $secret = null;
    private $shouldDeleteUser = false;

    private function getUser()
    {
        $users = User::all();
        if(!!$users) {
            self::$user = $users->first();
            $this->secret = "secret";
        }
        else {
            $this->shouldDeleteUser = true;
            self::$user = $this->createTestUser();
        }
    }

    private function createTestUser()
    {
        if(!$this->secret) {
            $this->secret = md5(env('APP_NAME', '-') . env('APP_KEY', '-') . now());
        }

        if(!self::$user) {
            self::$user = new User;
            self::$user->email = $this->secret . '@example.com';
            self::$user->name = $this->secret;
            self::$user->password = Hash::make($this->secret);
            self::$user->remember_token = Str::random(10);
            self::$user->email_verified_at = now();
            self::$user->save();
        }
    }

    private function deleteTestUser()
    {
        if(self::$user) {
            self::$user->delete();
            self::$user = null;
        }
    }

    private function login()
    {
        $this->getUser();
        if(!self::$session) {
            $response = $this->json('POST', '/api/token', [
                'email' => self::$user->email,
                'password' => $this->secret,
            ]);

            $response->assertStatus(200);

            self::$session = json_decode($response->content());
        }
    }

    private function logout()
    {
        if(self::$session) {
            $response = $this->json('DELETE', '/api/token', [], [
                'Authorization' => 'Bearer ' . self::$session->access_token,
            ]);

            $response->assertStatus(204);

            self::$session = null;
        }

        if($this->shouldDeleteUser) {
            $this->deleteTestUser();
        }
    }
}
