<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model// implements HasPresenter
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'page';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id', 'content', 'status'];

    protected $dates = ['deleted_at'];

    public function post() {
        return $this->hasOne('App\Models\Post', 'id', 'post_id');
    }

    /**
     * Attach the presenter class
     *
     * @return mixed
     *
    public function getPresenterClass() {
        return QuizPresenter::class;
    }
     */
}
