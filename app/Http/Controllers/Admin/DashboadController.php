<?php

namespace App\Http\Controllers\admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Model\Order;
use App\Model\Product;
use App\Model\ProductType;
use App\Model\User;
use App\Model\Member;
use App\Model\Comment;
use App\Model\News;
use App\Model\OrderDetail;
use Carbon\Carbon;

class DashboadController extends Controller
{
    public function index()
    {
        $listtypeproduct = ProductType::all();
        $product = Product::all();
        $user = User::all();
        $a = Carbon::now();
        $format = date('Y-m-d 0:0:0');
        //Số lương đơn hàng mới
        $odernew = Order::where('created_at', '>', $format)->get();
        //Đơn hang chưa xác nhận
        //$oder_cxd = Order::orderBy('created_at', 'desc')->where('status', 0)->get();
        //Tổng đơn hàng
        $thang = Carbon::now()->month;
        $nam = Carbon::now()->year;
        $oddetail  = Order::where('status', 2)
            ->join('order_details', 'order_details.bill_id', 'orders.id')
            ->select(
                DB::raw('sum(order_details.qty*order_details.product_price) as total'),
                DB::raw('MONTH(orders.created_at) as month')
            )
            ->whereMonth('orders.created_at', '=', $thang)
            ->groupBy('month')
            ->get();
        $odercxd = Order::where('status', 0)
            ->where('orders.deleted_at', null)
            ->join('order_details', 'order_details.bill_id', 'orders.id')
            ->select(
                DB::raw('sum(order_details.qty*order_details.product_price) as total, orders.id')
            )
            ->groupBy('orders.id')
            ->get();
        //Bình luận gần nhất
        $commet = Comment::orderBy('created_at', 'desc')->take(5)->where('status', 0)->get();

        //Đơn hàng
        //Người dung gần nhất
        $user_nearest = User::orderBy('created_at', 'desc')->take(5)->where('status', 1)->where('role', 1)->get();
        //Tổng đơn hàng
        $sloder = Order::all()->count();
        //Tổng người dung 
        $sluser = User::where('role', 1)->count();
        //Người dùng mới
        $usernew = User::where('created_at', '>', $format)->count();
        //Tổng sản phẩm
        $slProduct = Product::all()->count();
        //Tổng loại sản phẩm
        $slTypeProduct = ProductType::all()->count();
        //Tổng bình luận mới
        $slComment = Comment::all()->count();
        //Tổng thành viên 
        $slMember = Member::all()->count();
        //Tong bài viết
        $slNews = News::all()->count();
        //Bài viết mới 
        $new_nearest = News::orderBy('updated_at', 'desc')->take(3)->get();
        //Số lượng thành viên
        $slmember = Member::all()->count();
        //sản phẩm gần đây
        $product_nearest = Product::orderBy('updated_at', 'desc')->take(3)->get();




        $range = Carbon::now()->subDays(7);

        $orderYear = Order::where('created_at', '>=', $range)
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get([
                DB::raw('Date(created_at) as date'),
                DB::raw('COUNT(*) as value')
            ]);
        return view(
            'admin.trangchu.dashboard',
            compact(
                'sluser',
                'sloder',
                'slProduct',
                'slTypeProduct',
                'slComment',
                'slMember',
                'slNews',
                'odernew',
                'usernew',
                'slmember',
                'odercxd',
                'oddetail',
                'commet',
                'user',
                'product',
                'user_nearest',
                'product_nearest',
                'listtypeproduct',
                'new_nearest',
                'orderYear'
            )
        );
    }
}
