<?php

namespace App\Http\Model\Admin;

use Illuminate\Database\Eloquent\Model;

class Member_detail extends Model
{
//    关联的表
    protected $table = 'member_details';
//    主键字段
    protected $primaryKey = 'id';
//    黑名单
    protected $guarded = [];
//    是否自动维护 create_at 和 update_at
    public $timestamps = false;
    public function upper()
    {
        return $this->belongsTo(\App\Http\Model\Admin\Member::class,'id', 'id');
    }
}
