<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;


class Vendor extends Model
{
    use Notifiable;
    protected $fillable = ['name', 'password', 'mobile', 'address', 'email', 'category_id', 'active', 'logo', 'created_at', 'updated_at'];
    protected $hidden = ['created_at', 'updated_at'];

    public function scopeActive($query){
        return $query->where('active', 1);
    }

    public function getLogoAttribute($val){
        return ($val !== null) ? asset('assets/'.$val) : "";
    }

    public function scopeSelection($query){
        return $query->select('id', 'name', 'address', 'email', 'category_id', 'mobile', 'logo', 'active');
    }

    public function getActive(){
        return $this->active == 1 ?  'مفعل ': 'غير مفعل';
    }

    public function category(){
        return $this->belongsTo('App\Models\MainCategory', 'category_id', 'id');
    }

    public function setPasswordAttribute($password){
        $this->attributes['password'] = bcrypt($password);
    }
}
