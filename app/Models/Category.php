<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model // implements HasPresenter
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['deleted_at'];

    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }
}
