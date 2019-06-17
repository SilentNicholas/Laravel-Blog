<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

/**
 * Class User
 * @package App
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * @value int
     */
    public const IS_ADMIN = 1;
    /**
     * @value int
     */
    public const NOT_ADMIN = 0;
    /**
     * @value int
     */
    public const BANNED = 1;
    /**
     * @value int
     */
    public const NOT_BANNED = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'personal_status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @param array $fields
     * @return User
     */
    public static function add(array $fields)
    {
        $user = new static;
        $user->fill($fields);
        $user->generatePassword($fields['password']);
        $user->save();
        return $user;
    }

    /**
     * @param array $fields
     */
    public function edit(array $fields)
    {
        $this->fill($fields);
        $this->generatePassword($fields['password']);
        $this->save();
    }

    /**
     * @param string $password
     */
    public function generatePassword(string $password)
    {
        if ($password !== null) {
            $this->password = bcrypt($password);
        }
        $this->save();
    }

    /**
     * @throws \Exception
     */
    public function remove()
    {
        $this->removeAvatar();
        $this->delete();
    }

    /**
     * Delete image from storage
     */
    public function removeAvatar()
    {
        if ($this->image !== null) {
            Storage::delete('uploads/'. $this->image);
        }
    }

    /**
     * @param string $image
     */
    public function uploadAvatar(string $image)
    {
        if ($image === null){return;}
        if ($this->image !== null) {
            Storage::delete('uploads/'. $this->$image);
        }
        $fileName = str_random(10). '.'. $image->extension();
        $image->storeAs('uploads', $fileName);
        $this->image = $fileName;
        $this->save();
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        if ($this->image === null) {
            return '/img/no-image.png';
        }
        return '/uploads/'. $this->image;
    }

    /**
     * Makes the user admin
     */
    public function setAdmin()
    {
        $this->is_admin = static::IS_ADMIN;
        $this->save();
    }

    /**
     * Makes admin user
     */
    public function setNormal()
    {
        $this->is_admin = static::NOT_ADMIN;
        $this->save();
    }

    /**
     * @param int $value
     */
    public function toggleAdmin(int $value)
    {
        if ($value !== null) {
            return $this->setAdmin();
        }
        return $this->setNormal();
    }

    /**
     * Set users status to ban
     */
    public function setBanned()
    {
        $this->status = static::BANNED;
        $this->save();
    }

    /**
     * Set users status unbanned
     */
    public function setUnbanned()
    {
        $this->status = static::NOT_BANNED;
        $this->save();
    }

    /**
     * Toggle users status
     */
    public function toggleBanned()
    {
        if ($this->status === self::NOT_BANNED) {
            return $this->setBanned();
        }
        return $this->setUnbanned();
    }

    /**
     * @return int|string
     */
    public function getPersonalStatus()
    {
        if ($this->personal_status !== null) {
            return $this->personal_status;
        }
        return 'Без статуса';
    }
}
