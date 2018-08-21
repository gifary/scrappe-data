<?php
/**
 * Created by PhpStorm.
 * User: gifary
 * Date: 8/20/18
 * Time: 8:23 AM
 */

namespace App\Http\Models;


use App\Http\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Asin extends Model
{
    use UuidTrait;

    protected $fillable =['code','is_finished','number_of_comment'];
    public $incrementing = false;

    public function comments(){
        return $this->hasMany(AsinComment::class);
    }
}
