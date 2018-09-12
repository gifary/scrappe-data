<?php
/**
 * Created by PhpStorm.
 * User: gifary
 * Date: 8/20/18
 * Time: 8:24 AM
 */

namespace App\Http\Models;


use Illuminate\Database\Eloquent\Model;

class AsinComment extends Model
{
    protected $guarded =['_token','id'];

    /*protected $casts = [
        'tag' => 'array'
    ];*/

    public function images(){
        return $this->hasMany(AsinCommentImage::class);
    }

    public function tags(){
        return $this->hasMany(AsinCommentTag::class);
    }

    public function asin(){
        return $this->belongsTo(Asin::class);
    }
}
