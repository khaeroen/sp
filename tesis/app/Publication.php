<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'publications';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    protected $fillable = ['title', 'author', 'supervisor', 'email', 'abstract_en', 'abstract_id', 'keyword', 'cover', 'file', 'lampiran'];
}
