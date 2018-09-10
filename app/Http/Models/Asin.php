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

    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $fillable =['id','code','is_finished','number_of_comment','url'];

    public function comments(){
        return $this->hasMany(AsinComment::class);
    }
}
