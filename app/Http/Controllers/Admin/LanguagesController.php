<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;

class LanguagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $languages = Language::select()->paginate(PAGINATE);
        return view('admin.languages.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.languages.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageRequest $request)
    {
        try{
            Language::create(['name' => $request->name, 'abbr' => $request->abbr, 'direction' => $request->direction, 'active' => $request->active]);
            return redirect()->route('admin.languages')->with('success', 'language successfully saved!'); 
        }catch(Exception $ex){
            return redirect()->route('admin.languages')->with('error', 'there is an error, please check it!');
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
        $language = Language::find($id);
        if(!$language){
            return redirect()->route('admin.languages')->with('error', 'هذه اللغة غير موجودة');
        }
        return view('admin.languages.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageRequest $request, $id)
    {
        try{
            $language = Language::find($id);
            if(!$language){
                return redirect()->back()->with('error', 'هذه اللغة غير موجودة');
            }
            if (!$request->has('active'))
                $request->request->add(['active' => 0]); // wenn man etwas zu request zufügen möchte, schreibt man so
            $language->update($request->except('_token'));
            return redirect()->route('admin.languages')->with('success', 'language updated successfully!');
        }catch(Exception $ex){
            return redirect()->back()->with('error', 'هناك خطا ما يرجي المحاوله فيما بعد');

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
            $language = Language::find($id);
            if (!$language) {
                return redirect()->route('admin.languages', $id)->with(['error' => 'هذه اللغة غير موجوده']);
            }
            $language->delete();

            return redirect()->route('admin.languages')->with(['success' => 'تم حذف اللغة بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }
}
