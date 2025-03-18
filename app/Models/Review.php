<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * 口コミ登録したユーザーを取得
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class);
    }
}
