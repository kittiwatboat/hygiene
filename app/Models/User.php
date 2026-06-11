<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
     protected $fillable = [
    'name',
    'first_name',
    'last_name',
    'email',
    'phone',
    'password',
    'role',
    'status',
    'is_active',
    'last_login_at',
    'remark',
];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }
    public function getFullNameAttribute(): string
    {
        $fullName = trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));

        return $fullName !== '' ? $fullName : ($this->name ?: '-');
    }

    public function getRoleTextAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'ผู้ดูแลระบบ',
            'staff' => 'เจ้าหน้าที่',
            'technician' => 'ช่าง / เติมน้ำยา',
            default => 'ไม่ทราบสิทธิ์',
        };
    }

    public function getRoleBadgeClassAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'bg-label-danger',
            'staff' => 'bg-label-primary',
            'technician' => 'bg-label-warning',
            default => 'bg-label-secondary',
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'active' => 'ใช้งานปกติ',
            'inactive' => 'ปิดใช้งาน',
            'suspended' => 'ระงับการใช้งาน',
            default => 'ไม่ทราบสถานะ',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'active' => 'bg-label-success',
            'inactive' => 'bg-label-secondary',
            'suspended' => 'bg-label-danger',
            default => 'bg-label-secondary',
        };
    }
}
