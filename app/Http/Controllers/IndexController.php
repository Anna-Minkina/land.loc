<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\People;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;

class IndexController extends Controller
{

    public function index()
    {

        $pages = Page::all();
        $portfolios = Portfolio::get(array('name', 'filter', 'images'));
        $services = Service::where('id', '<', 20)->get();
        $peoples = People::take(3)->get();

        $tags = DB::table('portfolios')->distinct()->pluck('filter');

        //dd($tags);

        $menu = array();
        foreach ($pages as $page) {
            $item = array('title' => $page->name, 'alias' => $page->alias);
            array_push($menu, $item);
        }

        $item = array('title' => 'Services', 'alias' => 'service');
        array_push($menu, $item);

        $item = array('title' => 'Portfolio', 'alias' => 'Portfolio');
        array_push($menu, $item);

        $item = array('title' => 'Team', 'alias' => 'team');
        array_push($menu, $item);

        $item = array('title' => 'Contact', 'alias' => 'contact');
        array_push($menu, $item);

        return view('site.index', compact('menu', 'pages', 'services', 'portfolios', 'peoples', 'tags'));
    }


    public function sendMail(Request $request) {

        if ($request->isMethod('post')){

            $messages = [
                'required' => "Поле :attribute обязательно к заполнению",
                'email' => "Поле :attribute должно соответствовать email адресу"
            ];

            $this->validate($request, [

                'name'=>'required|max:255',
                'email'=>'required|email',
                'text'=>'required'
            ], $messages);
        }
        //dd($request);

        Mail::to(env('MAIL_REPLY_TO')) 
        ->send(new OrderShipped($request));

        $request->session()->flash('status', 'Email is sent');
        return redirect()->route('home');

    }
}



