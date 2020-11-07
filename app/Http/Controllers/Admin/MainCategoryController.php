<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Http\Requests\MainCategoriesRequest;
use DB; 
use Illuminate\Support\Str;

class MainCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Unter config/app.php legt man die Defaulu Language fest und Mithilfe folgender Helpers Function 
        // bestimmt man die Default Language
        //Man sollte den Befehk php artisan config:cache wenn mann default language geändert hat.

         $default_lang = getDefaultLang();
         $main_categories = MainCategory::where('translation_lang', $default_lang)->selection()->get();
         return view('admin.main_categories.index', compact('main_categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.main_categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MainCategoriesRequest $request)
    {
        //Hier wird das Array of Objects in collection umgewandelt
        try{
        $main_categories = collect($request->category);

        //Hier die Funktion filter benutzt um ein value (d.h. ein Objekt was arabisch ist)
        $filter =  $main_categories->filter(function($value, $key){
            return $value['abbr'] == getDefaultLang();
        });

        // Hier bekomme ich das erste Objekt mit detault Language als array Mithilfe der Funktion array_values
        //default_category ist ein array of objects und deswegen müssen wir sagen dass wir das erste haben wollen
        $default_category =  array_values($filter->all())[0];

        // jetzt wird dieses default_category zuerst gespeichert und wir wollen ihre id zurückhaben
        // Mithilfe der Funktion insertGetId() wird das gemacht
        if($request->has('photo')){
            $filePath = uploadImage('main_categories',$request->photo);
        }

        //Deise DB::beginTransaction() dient dazu dass alle Operationen zum Einfügen in die DB alle 
        // erst ausgeführt werden wenn dazwischen keine Probleme gibt
        DB::beginTransaction();
        $default_category_id = MainCategory::insertGetId([
            'translation_lang' => $default_category['abbr'],
            'translation_of' => 0,
            'name' => $default_category['name'],
            'slug' => $default_category['name'],
            'photo' => $filePath,
        ]);


        $categories =  $main_categories->filter(function($value, $key){
            return $value['abbr'] != getDefaultLang();
        });

        if(isset($categories) && $categories->count()){
            $categories_arr = [];
            foreach($categories as $category){
                $categories_arr[] = [
                    'translation_lang' => $category['abbr'],
            'translation_of' => $default_category_id,
            'name' => $category['name'],
            'slug' => $category['name'],
            'photo' => $filePath,
                ];
            }
            MainCategory::insert($categories_arr);
            DB::commit();
            return redirect()->route('admin.main_categories')->with('success', 'تم اضافة قسم بنجاح');
        }
        //Bis hier wenn alle Operationen richtig laufen dann machen wir commit und die Operationen 
        // werden in DB ausgeführt
        // Anderenfalls kommenm in Exception und geben einen Fehler
         }catch(\Exception $ex){
            DB::rollback();
            return redirect()->route('admin.main_categories')->with('error', 'لقد حدث مشكلة ولا يمكن التخزين');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // get specific categories and its translations
        $main_category = MainCategory::with('categories')->selection()->find($id);
        if(!$main_category)
            return redirect()->route('admin.main_categories')->with('error', 'هذا القسم غير موجود');
        return view('admin.main_categories.edit', compact('main_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MainCategoriesRequest $request, $id)
    {
        try{

            //return $request;
            $main_category = MainCategory::find($id);

            if(!$main_category)
            return redirect()->route('admin.main_categories')->with('error', 'هذا القسم غير موجود');

            //update
            $category = array_values($request->category)[0];
            if(!$request->has('category.0.active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            MainCategory::where('id', $id)
            ->update([
                'name' => $category['name'],
                'active' => $request->active,
            ]);
            // save image

            if ($request->has('photo')) {
                $filePath = uploadImage('main_categories', $request->photo);
                MainCategory::where('id', $id)
                    ->update([
                        'photo' => $filePath,
                    ]);
            }
            return redirect()->route('admin.main_categories')->with('success', 'تم التحديث بنجاح');
        } catch(\Exception $ex){
            return redirect()->route('admin.main_categories')->with('error', 'لقد حدث مشكلة');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory) 
                return redirect()->route('admin.main_categories', $id)->with(['error' => 'هذه القسم  غير موجود']);

            $vendors = $maincategory->vendors();
            if(isset($vendors) && $vendors->count() > 0){
                return redirect()->route('admin.main_categories', $id)->with(['error' => 'لا يمكن حذف هذا القسم لانه يحتوي على تجار']);
            }

            $image = Str::after($maincategory->photo, 'assets/');// gibt den Teil von url nach dem Wort assets
            $image = base_path('assets/'.$image);// gibt den gnauen Pfad des Photos im Computer
            unlink($image);

            // delete translation.. d.h. die anderen categories in anderen Sprachen ausser die default Sprache
            // categories() ist eine Realtionship innerhalb der Klasse MainCategory one to Many zwischen 
            // default language und die anderen Languages
            $maincategory->categories()->delete();
            $maincategory->delete();
            return redirect()->route('admin.main_categories')->with(['success' => 'تم حذف القسم بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.main_categories')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    public function changeStatus($id){
        try{
            $maincategory = MainCategory::find($id);
            if (!$maincategory) 
                return redirect()->route('admin.main_categories', $id)->with(['error' => 'هذه القسم  غير موجود']);

            $status = $maincategory->active == 0 ? 1 : 0;
            $maincategory->update(['active' => $status]);
            
            return redirect()->route('admin.main_categories')->with(['success' => 'تم تغيير حالة القسم بنجاح']);


        }catch (\Exception $ex){
            return redirect()->route('admin.main_categories')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }
}
