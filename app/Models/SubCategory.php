<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MainCategory;

class SubCategory extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = ['category_id', 'parent_id', 'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active', 'created_at', 'updated_at'];


    public function scopeActive($query){
        return $query->where('active', 1);
    }

    public function scopeDefaultCategories($query){
        return $query->where('translation_of', 0);
    }

    public function scopeSelection($query){
        return $query ->select('id', 'category_id', 'parent_id', 'translation_lang', 'translation_of', 'name', 'slug', 'photo', 'active');
    }
    
    public function getActive(){
        return $this->active == 1 ?  'مفعل ': 'غير مفعل';
    }

    public function getPhotoAttribute($val){
        return $val !== null ? asset('assets/'.$val) : "";
    }

    public function mainCategory(){
        return $this->belongsTo(MainCategory::class, 'category_id', 'id');
    }
  
}
