<?php

namespace CleancodeId\LaravelJwtGuard;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class LaravelJwtGuard implements Guard
{
    private $user;
    private $provider;
    private $publicKey;
    private $decodedToken;
    private $request;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->provider  = $provider;
        $this->request   = $request;
        $this->publicKey = env('JWT_PUBLIC_KEY');

        $this->authenticate();
    }

    /**
     * Decode token, validate and authenticate user
     *
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    private function authenticate()
    {
        $bearerToken = $this->request->bearerToken();

        if (empty($bearerToken)) {
            return false;
        }

        try {
            $this->decodedToken = JwtToken::decode($bearerToken, $this->publicKey);
        } catch (Exception $e) {
            throw new AuthenticationException('Token: ' . $e->getMessage());
        }

        if ($this->decodedToken) {
            $this->validate();
        }
    }


    /**
     * Determine if the current user is authenticated.
     *
     * @return bool
     */
    public function check()
    {
        return !is_null($this->user());
    }

    /**
     * Determine if the guard has a user instance.
     *
     * @return bool
     */
    public function hasUser()
    {
        return !is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     *
     * @return bool
     */
    public function guest()
    {
        return !$this->check();
    }

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        if (is_null($this->user)) {
            return null;
        }

        $this->user->token = (array) $this->decodedToken;

        return $this->user;
    }

    /**
     * Get the ID for the currently authenticated user.
     *
     * @return int|null
     */
    public function id()
    {
        if ($user = $this->user()) {
            return $this->user()->id;
        }
    }

    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        if (!$this->decodedToken) {
            return false;
        }

        $class = $this->provider->getModel();

        $user     = new $class();
        $user->id = $this->decodedToken->sub;

        if (isset($this->decodedToken->name)) {
            $user->name = $this->decodedToken->name;
        }

        $this->setUser($user);

        return true;
    }

    /**
     * Set the current user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
}
