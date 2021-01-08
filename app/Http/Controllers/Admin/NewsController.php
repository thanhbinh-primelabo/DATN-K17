<?php

namespace App\Http\Controllers\admin;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\News;
use App\Model\User;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listNews = News::all();
        $listUser = User::all();
        return view('admin.list-admin.ds-news.news', compact('listNews', 'listUser'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $listUser = User::all();
        return view('admin.list-admin.ds-news.actionnews', compact('listUser'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'title' => 'required|unique:news|max:254',
                'content' => 'required',
                'image' => 'image|required|mimes:jpeg,png,jpg,gif|max:2048'
            ],
            [
                'title.required' => 'Chưa nhập tiêu đề',
                'title.max' => 'Tiêu đề quá dài',
                'content.required' => 'Chưa nhập nội dung',
                'title.unique' => 'Tiêu đề này đã tồn tại',
                'image.required' => 'Ảnh sản phẩm không được để trống',
                'image.image' => 'Không đúng định dạng',
                'image.mimes' => 'Không đúng loại ảnh jpeg,png,jpg,gif,svg',
                'image.max' => 'Kích thước ảnh quá lớn',
            ]
        );
        if ($validate->passes()) {
            if (request()->hasFile('image')) {
                $file = $request->file('image');
                $fileName = $file->getClientOriginalName('image');
                $file->move('admin/image/posts', $fileName);
                $News = new News();
                $News->title = $request->title;
                $News->content = $request->content;
                $News->image = $fileName;
                $News->user_id_create = Auth::User()->id;
                $News->save();
                return redirect()->route('list-admin.ds-news.list');
            }
        } else {
            return back()->withErrors($validate)->withInput();
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
        $news = News::find($id);
        return view('admin.list-admin.ds-news.actionnews', compact('news'));
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
            'title' => 'required|max:254',
            'content' => 'required',
            'image' => 'image|required|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'title.required' => 'Chưa nhập tiêu đề',
            'title.max' => 'Tiêu đề quá dài',
            'content.required' => 'Chưa nhập nội dung',
            'image.required' => 'Ảnh sản phẩm không được để trống',
            'image.image' => 'Không đúng định dạng',
            'image.mimes' => 'Không đúng loại ảnh jpeg,png,jpg,gif,svg',
            'image.max' => 'Kích thước ảnh quá lớn'

        ]);

        $updataNews = News::find($id);
        if ($request->hasFile('image')) {
            $destinationPath = 'admin/image/posts/' . $updataNews->image;
            if (file_exists($destinationPath)) {
                unlink($destinationPath);
            }
            $file = $request->file('image');

            $fileName = $file->getClientOriginalName('image');
            $file->move('admin/image/posts', $fileName);
            $updataNews->title = $request->title;
            $updataNews->content = $request->content;
            $updataNews->image = $fileName;
            $updataNews->user_id_create = Auth::User()->id;
            $updataNews->save();
            return redirect()->route('list-admin.ds-news.list');
        } else {
            $updataNews->title = $request->title;
            $updataNews->content = $request->content;
            $updataNews->user_id_create = Auth::User()->id;
            $updataNews->save();
            return redirect()->route('list-admin.ds-news.list');
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
        $deleteNews = News::find($id);

        $destinationPath = 'admin/image/posts/' . $deleteNews->image;
        if (file_exists($destinationPath)) {
            unlink($destinationPath);
        }
        $deleteNews->delete();
        return redirect()->route('list-admin.ds-news.list');
    }
}
