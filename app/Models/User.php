<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['password' => 'hashed'];

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isDeveloper(): bool { return $this->role === 'developer'; }
    public function isUser(): bool { return $this->role === 'user'; }
    public function isAdminOrDeveloper(): bool { return in_array($this->role, ['admin', 'developer'], true); }
    public function events() { return $this->hasMany(Event::class); }
}