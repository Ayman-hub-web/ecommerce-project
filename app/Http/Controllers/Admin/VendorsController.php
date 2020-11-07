<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\MainCategory;
use App\Http\Requests\VendorRequest;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorCreated;
use DB;

class VendorsController extends Controller
{
    public function index(){
        $vendors = Vendor::selection()->paginate(PAGINATE);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create(){
        $categories = MainCategory::active()->where('translation_lang', getDefaultLang())->get();
        return view('admin.vendors.create', compact('categories'));
    }

    public function store(VendorRequest $request){
        try{

            //validation

            //insert in DB
            $filePath= "";
            if($request->has('logo')){
                $filePath = uploadImage('vendors',$request->logo);
            }
            if(!$request->has('active')){
                $request->request->add(['active'=> 0]);
            }else{
                $request->request->add(['active'=> 1]);
            }
            $vendor = Vendor::create([
                'name' => $request->name,
                'logo' => $filePath,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'category_id' => $request->category_id,
                'email' => $request->email,
                'password'=> $request->password,
                'active' => $request->active,
            ]);
            //Notification an den neuen Vendor schicken
            Notification::send($vendor, new VendorCreated($vendor));
            return redirect()->route('admin.vendors')->with('success', 'تم اضافة متجر بنجاح');

            //redirect message
        }catch(\Exception $ex){
            return redirect()->route('admin.vendors')->with('error', 'لقد حدث خطا ما');
        }
    }

    public function edit($id){
        try{
            $vendor = Vendor::find($id);
            if(!$vendor){
                return redirect()->route('admin.vendors')->with('error', 'هذا المتجر غير موجود');
            }else{
                $categories = MainCategory::where('translation_of', 0)->active()->get();

                return view('admin.vendors.edit', compact('vendor', 'categories'));
            }
        }catch(\Exception $ex){
            return redirect()->route('admin.vendors')->with('error', 'لقد حدث خطا ما');
        }
    }

    public function update(VendorRequest $request, $id){

        try{

            $vendor = Vendor::selection()->find($id);
            if(!$vendor)
                return redirect()->route('admin.vendors')->with('error', 'هذا المتجر غير موجود');
            // update for logo
            DB::beginTransaction();
             if ($request->has('logo')) {
               $filePath = uploadImage('vendors', $request->logo);

                Vendor::where('id', $id)
                ->update([
               'logo' => $filePath,
                    ]);
            }

            $data = $request->except('_token', 'id', 'logo');
            if($request->has('password')){
                $data['password'] = $request->password;
            }

            Vendor::where('id', $id)->update($data);
            DB::commit();
            return redirect()->route('admin.vendors')->with('success', 'تم التعديل بنجاح');

        }catch (\Exception $ex){
            DB::rollback();
            return $ex;
            return redirect()->route('admin.vendors')->with('error', 'لقد حدث خطا ما');
        }
    }

    public function destroy($id)
    {
        try {
            $vendor = Vendor::find($id);
            if (!$vendor) 
                return redirect()->route('admin.vendors', $id)->with(['error' => 'هذا المتجر  غير موجود']);
            // delete image of vendor
            $image = Str::after($vendors->logo, 'assets/');// gibt den Teil von url nach dem Wort assets
            $image = base_path('assets/'.$image);// gibt den gnauen Pfad des Photos im Computer
            unlink($image);
            // delete vendor
            $vendor->delete();
            return redirect()->route('admin.vendors')->with(['success' => 'تم حذف المتجر بنجاح']);

        } catch (\Exception $ex) {
            return redirect()->route('admin.vendors')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }

    public function changeStatus($id){
        try{
            $vendor = Vendor::find($id);
            if (!$vendor) 
                return redirect()->route('admin.vendors', $id)->with(['error' => 'هذا المتجر غير موجود']);

            $status = $vendor->active == 0 ? 1 : 0;
            $vendor->update(['active' => $status]);
            
            return redirect()->route('admin.vendors')->with(['success' => 'تم تغيير حالة المتجر بنجاح']);


        }catch (\Exception $ex){
            return redirect()->route('admin.vendors')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
        }
    }
}
