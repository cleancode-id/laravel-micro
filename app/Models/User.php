<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

/**
 * @property string $id
 * @property string $name
 */
class User extends Model implements AuthorizableContract
{
    use Notifiable, Authorizable;

    public $incrementing = false;

    protected $hidden = ['token'];
}
