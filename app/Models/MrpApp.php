<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MrpApp extends Model
{
    use HasFactory;
    protected $table = 'store_mrp_app';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'list_id', 'version', 'path', 'md5', 'resolution', 'size'];
}
