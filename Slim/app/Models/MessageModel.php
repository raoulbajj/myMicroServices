<?php

namespace App\Models;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Model;

class MessageModel extends Model
{
    protected $table = 'message';

    protected $fillable = [
        'content',
        'user_id',
        'discussion_id'
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }
}
