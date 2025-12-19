<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembinaan extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','judul','keterangan','tanggal_mulai','status'];

    protected $casts = [
        'tanggal_mulai' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
