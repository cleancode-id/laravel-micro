<?php

namespace App\Providers;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Auth::viaRequest('jwt-guard', function (Request $request) {
            return $this->authenticate($request);
        });
    }

    /**
     * Decode token, validate and authenticate user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return User|null
     * @throws \Illuminate\Auth\AuthenticationException
     */
    private function authenticate(Request $request): ?User
    {
        $jwtPublicKey = config('jwt.public_key');

        $bearerToken = $request->bearerToken();

        if (empty($bearerToken)) {
            return null;
        }

        try {
            $decodedToken = $this->decode($bearerToken, $jwtPublicKey);
        } catch (Exception $e) {
            throw new AuthenticationException('Token: ' . $e->getMessage());
        }

        if (! $decodedToken) {
            return null;
        }

        $user     = new User();
        $user->id = $decodedToken->sub;

        if (isset($decodedToken->name)) {
            $user->name = $decodedToken->name;
        }

        return $user;
    }

    /**
     * Decode a JWT token
     *
     * @param  string  $token
     * @param  string  $publicKey
     * @return object|null
     */
    public function decode(string $token, string $publicKey): ?object
    {
        $publicKey = $this->buildPublicKey($publicKey);

        return $token ? JWT::decode($token, $publicKey, ['RS256']) : null;
    }

    /**
     * Build a valid public key from a string
     *
     * @param  string  $key
     * @return mixed
     */
    private function buildPublicKey(string $key): string
    {
        return "-----BEGIN PUBLIC KEY-----\n" . wordwrap($key, 64, "\n", true) . "\n-----END PUBLIC KEY-----";
    }
}
