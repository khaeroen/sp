<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'articles';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'author', 'supervisor', 'email', 'abstract_en', 'abstract_id', 'keyword', 'cover', 'bab_1', 'bab_2', 'bab_3', 'bab_4', 'bab_5', 'bab_6', 'lampiran'];

    
}
