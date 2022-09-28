<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Page;

class PagesAddController extends Controller
{
    //
    public function execute(Request $request) {

        if($request->isMethod('post')) {
            $input = $request->except('_token');

            $messages = [

                'required'=>'Поле :attribute обязательно к заполнению',
                'unique'=>'Поле :attribute должно быть уникальным'
            ];

            $validator = Validator::make($input, [

                'name'=>'required|max:255',
                'alias'=>'required|unique:pages|max:255',
                'text'=>'required'

            ], $messages);

            if($validator->fails()) {

                return redirect()->route('pagesAdd')->withErrors($validator)->withInput();
            }
            if($request->hasFile('images')) {  //проверка - загружается ли файл на сервер

                $file = $request->file('images');

                $input['images'] = $file->getClientOriginalName();

                $file->move(public_path().'/img',$input['images']); //перемещает файл 
                //из временного хранилища в назначенную директорию
            }

            $page = new Page();
            $page->fill($input);

            if($page->save()) {

                return redirect('admin')->with('status','Страница добавлена');
            }
        }
        
        
        if(view()->exists('admin.pages_add')) {

            $data = [

                'title' => 'Новая страница'
            ];
            return view('admin.pages_add',$data);
        }
        abort(404);
    }
}
