<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        //往layout视图传递数据，生成左侧菜单,可以为视图列表，往多个视图传数据
        view()->composer('admin.layout',function($view){

            //获取菜单列表
            $menus = \App\Models\Permission::with([
                'childs'=>function($query){$query->with('icon');}
                ,'icon'])->where('parent_id',0)->orderBy('sort','desc')->get();
            //$user = \app\Models\User::

            //获取当前登录用户信息
            $user = Auth::user();


            //获取当前登录用户未读信息
            $unreadMessage = \App\Models\Message::where('read',1)->where('accept_uuid',auth()->user()->uuid)->count();

            //将获取到的数据传递给视图
            $view->with('menus',$menus);
            $view->with('unreadMessage',$unreadMessage);
            $view->with('permission',$user->can('system.manage'));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
