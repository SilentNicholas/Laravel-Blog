<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Comment
 * @package App
 */
class Comment extends Model
{
    /**
     * Status for comment
     * @value int
     */
    public const ALLOW = 1;
    /**
     * Status for comment
     * @value int
     */
    public const DISALLOW = 0;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Save allow status for comment. People can see him.
     */
    public function allow()
    {
        $this->status = static::ALLOW;
        $this->save();
    }

    /**
     * Save disallow status for comment. People can not see him.
     */
    public function disallow()
    {
        $this->status = static::DISALLOW;
        $this->save();
    }

    /**
     * Toggle status for comment. If people can see him or not.
     */
    public function toggleStatus()
    {
        if ($this->status === 1) {
            return $this->disallow();
        }
        return $this->allow();
    }
}
