<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS = [
        'pending' => 'درحال بررسی',
        'closed' => 'بسته شده',
    ];


    const COMPANIES = [
        'parso' => 'پرسو تجارت ایرانیان',
        'barman' => 'بارمان سیستم',
        'adaktejarat' => 'آداک تجارت خورشید قشم',
    ];

    public function sender()
    {
        return $this->belongsTo(User::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
