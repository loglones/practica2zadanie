<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class User extends Model implements IdentityInterface
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'users';
    protected $fillable = [
        'name',
        'login',
        'password',
        'role' // 'admin' или 'employee'
    ];

    protected static function booted()
    {
        static::creating(function ($user) {
            $user->password = password_hash($user->password, PASSWORD_DEFAULT); // Используем безопасное хеширование
        });
    }

    public function findIdentity(int $id): ?IdentityInterface
    {
        return self::where('id', $id)->first();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function attemptIdentity(array $credentials): ?IdentityInterface
    {
        // Временная "заглушка" для теста
        $testUsers = [
            'admin' => [
                'id' => 1,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // пароль "password"
                'role' => 'admin'
            ],
            'employee' => [
                'id' => 2,
                'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
                'role' => 'employee'
            ]
        ];

        if (isset($testUsers[$credentials['login']]) &&
            password_verify($credentials['password'], $testUsers[$credentials['login']]['password'])) {
            $user = new self();
            $user->id = $testUsers[$credentials['login']]['id'];
            $user->role = $testUsers[$credentials['login']]['role'];
            return $user;
        }

        return null;
    }
//    public function attemptIdentity(array $credentials): ?IdentityInterface
//    {
//        return self::where([
//            'login' => $credentials['login'],
//            'password' => md5($credentials['password']) // Временное решение
//        ])->first();
//    }
}