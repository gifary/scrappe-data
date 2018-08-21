<?php
/**
 * Created by PhpStorm.
 * User: gifary
 * Date: 8/20/18
 * Time: 4:10 PM
 */

namespace App\Http\Traits;

use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;
use Ramsey\Uuid\Uuid ;

trait UuidTrait
{
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            try {
                $model->id = Uuid::uuid4()->toString();
            } catch (UnsatisfiedDependencyException $e) {
                abort(500, $e->getMessage());
            }
        });
    }
}
