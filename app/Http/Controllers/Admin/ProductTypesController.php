<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\ProductType;

class ProductTypesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listtypeproduct = ProductType::all();
        return view('admin.list-admin.ds-typeproduct.typeproduct', compact('listtypeproduct'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('admin.list-admin.ds-typeproduct.actiontypeproduct')->with(['flash_message' => 'Thêm thành công !']);;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'type_name' => 'required|unique:product_types|max:124',
            'description' => 'required',
        ], [
            'type_name.required' => 'chưa nhập loại sản phẩm',
            'type_name.max' => 'tên loại sản phẩm quá dài',
            'description.required' => 'chưa nhập mô tả',
            'type_name.unique' => 'Loại này đã tồn tại',
            // 'image.image'=>'không đúng định dạng'
        ]);
        $typeproduct = new ProductType();
        $typeproduct->type_name = $request->type_name;
        $typeproduct->description = $request->description;
        $typeproduct->save();
        //    if($typeproduct->save()){
        //     toast('Thêm thành công!','success','top-right'); 
        //    }
        return redirect()->route('list-admin.ds-typeproduct.list');
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
        $typeproduct = ProductType::find($id);
        return view('admin.list-admin.ds-typeproduct.actiontypeproduct', compact('typeproduct'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'type_name' => 'required|max:124',
            'description' => 'required',

        ], [
            'type_name.required' => 'Chưa nhập loại sản phẩm',
            'type_name.max' => 'tên loại sản phẩm quá dài',
            'description.required' => 'Chưa nhập mô tả',
            // 'type_name.unique'=>'Loại này đã tồn tại',

        ]);
        $updatatypeproduct = ProductType::find($id);
        $updatatypeproduct->type_name = $request->type_name;
        $updatatypeproduct->description = $request->description;
        $updatatypeproduct->save();
        //    if($updatatypeproduct->save()){
        //     toast('Cập nhật thành công!','success','top-right'); 
        //    }
        return redirect()->route('list-admin.ds-typeproduct.list');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deletetypeproduct = ProductType::find($id);
        $deletetypeproduct->delete();
        return redirect()->route('list-admin.ds-typeproduct.list');
    }
}
