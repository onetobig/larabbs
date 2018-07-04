<?php
namespace Tests\Traits;

use App\Models\User;

trait ActingJWTUser
{
    public function JWTActingAs(User $user)
    {
        $token = \Auth::guard('api')->fromUser($this->user);
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $token]);
        return $this;
    }
}
