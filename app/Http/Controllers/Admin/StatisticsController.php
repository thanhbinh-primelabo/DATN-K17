<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Model\OrderDetail;
use App\Model\Order;
use App\Model\User;

class StatisticsController extends Controller
{
	public function sumPriceByYear(Request $request)
	{
		$year = Carbon::now()->year;
		if ($request->ajax()) {
			$total = Order::where('status', 2)
				->join('order_details', 'order_details.bill_id', 'orders.id')
				->select(
					DB::raw('sum(order_details.qty*order_details.product_price) as total'),
					DB::raw('MONTH(orders.created_at) as month')
				)
				->whereYear('order_details.created_at', '=', $request->nam)
				->groupBy('month')
				->get();
			return $total;
		}
		$total = Order::where('status', 2)
			->join('order_details', 'order_details.bill_id', 'orders.id')
			->select(
				DB::raw('sum(order_details.qty*order_details.product_price) as total'),
				DB::raw('MONTH(orders.created_at) as month')
			)
			->whereYear('order_details.created_at', '=', $year)
			->groupBy('month')
			->get();
		return view('admin.list-admin.ds-thongke.doanhthu', compact('total'));
	}

	public function seachPriceByYear(Request $request)
	{
		if ($request->ajax()) {
			if ($request->thang != null) {
				$total = Order::where('status', 2)
					->whereYear('orders.created_at', '=', $request->nam)
					->whereMonth('orders.created_at', '=', $request->thang)
					->join('order_details', 'order_details.bill_id', 'orders.id')
					->select(
						DB::raw('sum(order_details.qty*order_details.product_price) as total'),
						DB::raw('DAY(orders.created_at) as day')
					)
					->groupBy('day')
					->get();
				return $total;
			}
		}
	}

	public function sumOrderByYear(Request $request)
	{
		$year = Carbon::now()->year;
		if ($request->ajax()) {
			$total = Order::select(
				DB::raw('count(*) as total'),
				DB::raw('MONTH(created_at) as month')
			)
				->whereYear('created_at', '=', $request->nam)
				->groupBy('month')
				->get();
			return $total;
		}
		$total = Order::select(
			DB::raw('count(*) as total'),
			DB::raw('MONTH(created_at) as month')
		)
			->whereYear('created_at', '=', $year)
			->groupBy('month')
			->get();
		return view('admin.list-admin.ds-thongke.donhang', compact('total'));
	}

	public function seachOrderByYear(Request $request)
	{
		$year = Carbon::now()->year;
		if ($request->ajax()) {
			$total = Order::select(
				DB::raw('count(*) as total'),
				DB::raw('DAY(created_at) as day')
			)
				->whereYear('created_at', '=', $request->nam)
				->whereMonth('created_at', '=', $request->thang)
				->groupBy('day')
				->get();
			return $total;
		}
	}

	public function sumUserByYear(Request $request)
	{
		$year = Carbon::now()->year;
		if ($request->ajax()) {
			$total = User::where('role', 1)
				->select(
					DB::raw('count(*) as total'),
					DB::raw('MONTH(created_at) as month')
				)
				->whereYear('created_at', '=', $request->nam)
				->groupBy('month')
				->get();
			return $total;
		}
		$total = User::where('role', 1)
			->select(
				DB::raw('count(*) as total'),
				DB::raw('MONTH(created_at) as month')
			)
			->whereYear('created_at', '=', $year)
			->groupBy('month')
			->get();
		return view('admin.list-admin.ds-thongke.nguoidung', compact('total'));
	}
}
