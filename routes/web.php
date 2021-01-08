<?php

use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\checkAdmin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// User

Route::post('/checkout/list-address', 'User\CheckoutController@listAddress');

// Trang chủ
Route::get('/', 'User\HomeController@index')->name('home');

// Call ajax load trang
Route::post('/', 'User\HomeController@index');

// Thêm sản phẩm vào giỏ hàng home
Route::post('/cart', 'User\CartController@addCart');

// Xóa sản phẩm ở giỏ hàng home
Route::delete('/cart', 'User\CartController@delete');

// Trang shopping-cart
Route::get('/shopping-cart', 'User\CheckoutController@index')->middleware(checkAdmin::class);

// Cập nhật số lượng shopping-cart
Route::post('/shopping-cart', 'User\CheckoutController@update')->middleware(checkAdmin::class);

// Xóa sản phẩm ở shopping-cart
Route::post('/shopping-cart/delete', 'User\CheckoutController@delete')->middleware(checkAdmin::class);

// Trang checkout
Route::get('/checkout', 'User\CheckoutController@viewCheckout')->middleware(checkAdmin::class);

// Xử lí đơn hàng
Route::post('/checkout', 'User\CheckoutController@createCheckout')->middleware(checkAdmin::class);

// Trang loại sản phẩm 
Route::get('/chi-tiet-{name}.{id}', 'User\HomeController@typeProduct');

// Call ajax load trang loai spham
Route::post('/chi-tiet-{name}.{id}', 'User\HomeController@pageLoadTypeProduct');

// Trang chi tiết sản phẩm
Route::get('/{name}.{id}', 'User\DetailProductController@index');

// Call ajax load trang chi tiết
Route::post('/{name}.{id}/load', 'User\DetailProductController@pageLoadDetailProduct');

// Xử lí comment ở chi tiết sản phẩm
Route::post('/{name}.{id}', 'User\DetailProductController@index');

// Xử lí xóa comment chi tiết sản phẩm
Route::post('/{name}.{id}/delete', 'User\DetailProductController@deleteComment');

// Xử lí thêm giỏ hàng ở detail sản phẩm
Route::post('/{name}.{id}/add-cart', 'User\DetailProductController@addCart');

// Xử lí seach gửi request
Route::get('/seach', 'User\HomeController@seach');

// Phân trang seach
Route::post('/seach', 'User\HomeController@seach');

// Trang tin tức
Route::get('/tin-tuc', 'User\NewsController@index');

// Trang chi tiết bài viết
Route::get('/tin-tuc/{url}/{id}', 'User\NewsController@detailPost');

Route::get('/gioi-thieu', 'User\HomeController@introduce');

Route::group(['prefix' => 'account', 'namespace' => 'User'], function () {
	// Đăng kí
	Route::get('/register', 'RegisterController@index');
	// Xử lí đăng kí
	Route::post('/register', 'RegisterController@register');

	// Đăng nhập
	Route::get('/login', 'LoginController@index');

	// Xử lí đăng nhập
	Route::post('/login', 'LoginController@login');

	// Xử lí đăng xuất
	Route::get('/logout', 'LoginController@logout');

	// Thông tin khách hàng
	Route::get('/profile', 'ProfileController@index')->middleware(CheckLogin::class);

	// Cập nhật thông tin khách hàng
	Route::post('/profile', 'ProfileController@change_profile')->middleware(CheckLogin::class);

	// Thay đổi mật khẩu
	Route::get('/password/change', function () {
		return view('user.thongtin.change_password');
	})->middleware(CheckLogin::class);

	// Xử lí thay đổi mật khẩu
	Route::post('/password/change', 'ProfileController@change_password')->middleware(CheckLogin::class);

	// Địa chỉ
	Route::get('/address', 'ProfileController@view_address')->middleware(CheckLogin::class);

	// Thêm địa chỉ
	Route::get('/address/create', 'ProfileController@CreateAddress')->middleware(CheckLogin::class);

	// Cập nhật địa chỉ và sdt
	Route::post('/address/edit', 'ProfileController@UpdateAddress')->middleware(CheckLogin::class);

	// Lấy lại mật khẩu
	Route::get('/recovery', function () {
		return view('user.dangnhap.reset_password');
	});

	// Xử lí lấy lại mật khẩu
	Route::post('/recovery', 'LoginController@sendMail_resetPassword');

	// Mật khẩu mới
	Route::get('/password/reset', function () {
		return view('user.dangnhap.new_password');
	});

	// Xử lí mật khẩu mới
	Route::post('/password/reset', 'LoginController@newPassword');

	// Đơn mua
	Route::get('/purchase', 'ProfileController@viewPurchase');

	Route::get('/purchase/{id}', 'ProfileController@deletePurchase');

	Route::get('/purchase/detail/{id}', 'ProfileController@detailPurchase');
});

Route::get('/chinh-sach-quy-dinh', function () {
	return view('user.chinhsach.quydinh');
});

Route::get('/chinh-sach-thanh-toan', function () {
	return view('user.chinhsach.thanhtoan');
});

Route::get('/chinh-sach-bao-mat', function () {
	return view('user.chinhsach.baomat');
});

///////////////////////////////////////////////////////////////////////////////////////////////////////

// Admin
// Đăng nhập
Route::get('/login', function () {
	return view('admin.dangnhap.login');
});

// Xử lí đăng nhập Admin
Route::post('/login', 'User\LoginController@login');


// Xử lí đăng xuất
Route::get('/logout', 'User\LoginController@logout');

// Lấy lại mật khẩu
Route::get('/recovery', function () {
	return view('admin.dangnhap.reset_password');
});

// Xử lí lấy lại mật khẩu
Route::post('/recovery', 'User\LoginController@sendMail_resetPassword');

// Mật khẩu mới
Route::get('/password/reset', function () {
	return view('user.dangnhap.new_password');
});

// Xử lí mật khẩu mới
Route::post('/password/reset', 'User\LoginController@newPassword');

Route::group(['prefix' => 'admin', 'namespace' => 'User'], function () {

	// Thông tin admin
	Route::get('/profile', 'ProfileController@index')->middleware(CheckLogin::class);

	// Thay đổi thông tin admin
	Route::post('/profile', 'ProfileController@change_profile')->middleware(CheckLogin::class);

	// Thay đổi mật khẩu
	Route::get('/password/change', function () {
		return view('admin.thongtin.change_password');
	})->middleware(CheckLogin::class);

	// Xử lí thay đổi mật khẩu
	Route::post('/password/change', 'ProfileController@change_password');
});

Route::get('/admin/dashboard', 'Admin\DashboadController@index')->name('dashboard')->middleware(CheckLogin::class);


Route::group(['prefix' => 'admin/list-product', 'middleware' => 'CheckLogin'], function () {
	Route::name('list-admin.ds-product.')->group(function () {
		Route::get('/', 'Admin\ProductsController@index')->name('list');
		Route::get('add', 'Admin\ProductsController@create')->name('add');
		Route::post('add', 'Admin\ProductsController@store')->name('edit-add');
		Route::get('update/{id}', 'Admin\ProductsController@edit')->name('update');
		Route::post('update/{id}', 'Admin\ProductsController@update')->name('edit-update');
		Route::get('delete/{id}', 'Admin\ProductsController@destroy')->name('delete');
	});
});

Route::group(['prefix' => 'admin/list-typeproduct', 'middleware' => 'CheckLogin'], function () {
	Route::name('list-admin.ds-typeproduct.')->group(function () {
		Route::get('/', 'Admin\ProductTypesController@index')->name('list');
		Route::get('add', 'Admin\ProductTypesController@create')->name('add');
		Route::post('add', 'Admin\ProductTypesController@store')->name('edit-add');
		Route::get('update/{id}', 'Admin\ProductTypesController@edit')->name('update');
		Route::post('update/{id}', 'Admin\ProductTypesController@update')->name('edit-update');
		Route::get('delete/{id}', 'Admin\ProductTypesController@destroy')->name('delete');
	});
});

Route::group(['prefix' => 'admin/list-user', 'middleware' => 'CheckLogin'], function () {
	Route::name('list-admin.ds-user.')->group(function () {
		Route::get('/', 'Admin\UsersController@index')->name('list');
		Route::get('update/{id}', 'Admin\UsersController@edit')->name('update');
		Route::get('delete/{id}', 'Admin\UsersController@destroy')->name('delete');
	});
});
Route::group(['prefix' => 'admin/list-news', 'middleware' => 'CheckLogin'], function () {
	Route::name('list-admin.ds-news.')->group(function () {
		Route::get('/', 'Admin\NewsController@index')->name('list');
		Route::get('add', 'Admin\NewsController@create')->name('add');
		Route::post('add', 'Admin\NewsController@store')->name('edit-add');
		Route::get('update/{id}', 'Admin\NewsController@edit')->name('update');
		Route::post('update/{id}', 'Admin\NewsController@update')->name('edit-update');
		Route::get('delete/{id}', 'Admin\NewsController@destroy')->name('delete');
	});
});
Route::group(['prefix' => 'admin/list-comment', 'middleware' => 'CheckLogin'], function () {
	Route::name('list-admin.ds-comment.')->group(function () {
		Route::get('/', 'Admin\CommentsController@index')->name('list');
		Route::get('update/{id}', 'Admin\CommentsController@edit')->name('update');
	});
});
Route::group(['prefix' => 'admin/list-member', 'middleware' => 'CheckLogin'], function () {
	Route::name('list-admin.ds-member.')->group(function () {
		Route::get('/', 'Admin\MembersController@index')->name('list');
		Route::post('/', 'Admin\MembersController@update')->name('update');
	});
});
Route::group(['prefix' => 'admin/list-order', 'middleware' => 'CheckLogin'], function () {
	Route::name('list-admin.ds-order.')->group(function () {
		Route::get('/', 'Admin\OrdersController@index')->name('list');
		Route::post('/', 'Admin\OrdersController@update');
		Route::get('detail/{id}', 'Admin\OrdersController@indexDetail')->name('detail');
	});
});

Route::group(['prefix' => 'admin/thong-ke', 'middleware' => 'CheckLogin'], function () {
	Route::name('thong-ke.')->group(function () {
		Route::get('/doanh-thu', 'Admin\StatisticsController@sumPriceByYear');
		Route::post('/doanh-thu', 'Admin\StatisticsController@seachPriceByYear');
		Route::get('/don-hang', 'Admin\StatisticsController@sumOrderByYear');
		Route::post('/don-hang', 'Admin\StatisticsController@seachOrderByYear');
		Route::get('/nguoi-dung', 'Admin\StatisticsController@sumUserByYear');
	});
});
