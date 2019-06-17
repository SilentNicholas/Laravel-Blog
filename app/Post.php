<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

/**
 * Class Post
 * @package App
 */
class Post extends Model
{
    use Sluggable;

    /**
     * @value int
     */
    public const IS_DRAFT = 0;
    /**
     * @value int
     */
    public const IS_PUBLIC = 1;
    /**
     * @value int
     */
    public const IS_FEATURED = 1;
    /**
     * @value int
     */
    public const IS_STANDART = 0;

    /**
     * @var array
     */
    protected $fillable = ['title', 'content', 'date', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'posts_tags', 'post_id', 'tag_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * @param array $fields
     * @return Post
     */
    public static function add(array $fields)
    {
        $post = new static;
        $post->fill($fields);
        $post->user_id = 1;
        $post->save();
        return $post;
    }

    /**
     * @param array $fields
     */
    public function edit(array $fields)
    {
        $this->fill($fields);
        $this->save();
    }

    /**
     * @throws \Exception
     */
    public function remove()
    {
        $this->removeImage();
        $this->delete();
    }

    /**
     * Delete image from Storage.
     */
    public function removeImage()
    {
        if ($this->image !== null)
        {
            Storage::delete('uploads/'. $this->image);
        }
    }

    /**
     * @param string $image
     */
    public function uploadImage(string $image)
    {
        if ($image === null){return;}
        $this->removeImage();
        $fileName = str_random(10). '.'. $image->extension();
        $image->storeAs('uploads', $fileName);
        $this->image = $fileName;
        $this->save();
    }

    /**
     * @return string
     */
    public function getImage()
    {
        if ($this->image === null) {
            return '/img/no-image.png';
        }
        return '/uploads/'. $this->image;
    }

    /**
     * @param int $id
     */
    public function setCategory(int $id)
    {
        if ($id === null){return;}
        $this->category_id = $id;
        $this->save();
    }

    /**
     * @param array $ids
     */
    public function setTags(array $ids)
    {
        if ($ids === null){return;}
        $this->tags()->sync($ids);
    }

    /**
     * Set Draft status for Post
     */
    public function setDraft()
    {
        $this->status = static::IS_DRAFT;
        $this->save();
    }

    /**
     * Set public status for Post
     */
    public function setPublic()
    {
        $this->status = static::IS_PUBLIC;
        $this->save();
    }

    /**
     * @param int $value
     */
    public function toggleStatus(int $value)
    {
        if ($value !== null) {
            return $this->setDraft();
        }
        return $this->setPublic();

    }

    /**
     * Set featured status for Post
     */
    public function setFeatured()
    {
        $this->is_featured = static::IS_FEATURED;
        $this->save();
    }

    /**
     * Set not featured or standart status for Post
     */
    public function setStandart()
    {
        $this->is_featured = static::IS_STANDART;
        $this->save();
    }

    /**
     * @param int $value
     */
    public function toggleFeatured(int $value)
    {
        if ($value === null) {
            return $this->setStandart();
        }
        return $this->setFeatured();

    }

    /**
     * @param int $value
     */
    public function setDateAttribute(int $value)
    {
        $date = Carbon::createFromFormat('d/m/y', $value)->format('Y-m-d');
        $this->attributes['date'] = $date;
    }

    /**
     * @param int $value
     * @return string
     */
    public function getDateAttribute(int $value)
    {
        if ($value === null) {
            return Carbon::createFromTimestamp(time())->format('d/m/y');
        }
        $date = Carbon::createFromFormat('Y-m-d', $value)->format('d/m/y');
        return $date;
    }

    /**
     * @return string
     */
    public function getCategoryTitle()
    {
        return ($this->category !== null) ? $this->category->title : 'Без категории';
    }

    /**
     * @return string
     */
    public function getTagsTitle()
    {
        return (!$this->tags->isEmpty()) ? implode(', ', $this->tags->pluck('title')->all()) : 'Без тегов';
    }

    /**
     * @return string
     */
    public function getDate()
    {
        if ($this->date !== null) {
            return Carbon::createFromFormat('d/m/y', $this->date)->format('F d, Y');
        }
        return Carbon::createFromTimestamp(time())->format('F d, Y');
    }

    /**
     * @return int|null
     */
    public function getCategoryId()
    {
        return ($this->category !== null) ? $this->category->id : null;
    }

    /**
     * @return int id
     */
    public function hasPrevious()
    {
        return self::where('id', '<', $this->id)->max('id');
    }

    /**
     * @return int id
     */
    public function hasNext()
    {
        return self::where('id', '>', $this->id)->min('id');
    }

    /**
     * @return Post
     */
    public function getPrevious()
    {
        $postId = $this->hasPrevious();
        return self::find($postId);
    }

    /**
     * @return Post
     */
    public function getNext()
    {
        $postId = $this->hasNext();
        return self::find($postId);
    }

    /**
     * @return Post[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelated()
    {
        return self::all()->except($this->id);
    }

    /**
     * @return bool
     */
    public function hasCategory()
    {
        return $this->category !== null ? true : false;
    }

    /**
     * @return array
     */
    public static function getPopularPosts()
    {
        return self::orderBy('views', 'desc')->take(3)->get();
    }

    /**
     * @return array
     */
    public static function getFeaturedPosts()
    {
        return self::where('is_featured', 1)->get();
    }

    /**
     * @return array
     */
    public static function getRecentPosts()
    {
        return self::orderBy('date', 'desc')->take(4)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getComments()
    {
        return $this->comments()->where('status', 1)->get();
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return ($this->status === 1) ? 'Опубликовано' : 'Черновик';
    }
}
