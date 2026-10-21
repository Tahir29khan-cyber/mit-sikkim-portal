<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    // Helper to get boolean value easily
    public static function bool($key)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? (bool) $setting->value : true;
    }
}