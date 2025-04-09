<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EmailVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'verification_code',
        'expires_at',
        'verified',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified' => 'boolean',
    ];

    /**
     * Generate a new verification code for the given email
     *
     * @param string $email
     * @return string
     */
    public static function generateCode(string $email): string
    {
        // Delete any existing verification codes for this email
        self::where('email', $email)->delete();

        // Generate a random 6-digit code
        $code = (string) random_int(100000, 999999);

        // Create a new verification record
        self::create([
            'email' => $email,
            'verification_code' => $code,
            'expires_at' => now()->addMinutes(60), // Code expires in 60 minutes
            'verified' => false,
        ]);

        return $code;
    }

    /**
     * Verify a code for the given email
     *
     * @param string $email
     * @param string $code
     * @return bool
     */
    public static function verifyCode(string $email, string $code): bool
    {
        $verification = self::where('email', $email)
            ->where('verification_code', $code)
            ->where('expires_at', '>', now())
            ->where('verified', false)
            ->first();

        if (!$verification) {
            return false;
        }

        $verification->update(['verified' => true]);

        return true;
    }
}
