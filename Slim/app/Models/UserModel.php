<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserModel extends Model
{
    protected $table = 'user';

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    public function messages()
    {
        return $this->hasMany(MessageModel::class, 'user_id');
    }
}
