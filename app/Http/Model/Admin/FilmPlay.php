<?php

namespace App\Http\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class FilmPlay extends Model
{
    //关联的表
    protected $table = 'film_plays';
    //主键
    protected $primaryKey = 'id';
    //黑名单
    protected $guarded = [];

    //是否自动维护 create_at 和 update_at
    public $timestamps = false;

    public function detail()
    {
        return $this -> hasOne('\App\Http\Model\Admin\Film','id','fid');
    }

    public function room()
    {
        return $this -> hasOne('\App\Http\Model\Admin\FilmRoom', 'id', 'rid');
    }
}
