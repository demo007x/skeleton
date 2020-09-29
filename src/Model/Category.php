<?php

declare (strict_types=1);
namespace Bclfp\Skeleton\Model;

use Hyperf\Database\Model\SoftDeletes;
use Hyperf\DbConnection\Model\Model;
/**
 * @property int $id 
 * @property int $pid 
 * @property string $title 
 * @property int $sort 
 * @property string $description 
 * @property string $img
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at 
 * @property string $deleted_at 
 */
class Category extends Model
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'pid', 'title', 'sort', 'description', 'img','created_at', 'updated_at', 'deleted_at'];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'sort' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];
}