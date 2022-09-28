<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Portfolio;

class PortfolioAddController extends Controller
{
    //
    public function execute(Request $request) {

        if ($request->isMethod('post')) {
            $input = $request->except('_token');

            $messages = [

                'required' => 'Поле :attribute обязательно к заполнению',
                'unique' => 'Поле :attribute должно быть уникальным'
            ];

            $validator = Validator::make($input, [

                'name' => 'required|max:255',
                'filter'=>'required|max:255'
                                
            ], $messages);

            if ($validator->fails()) {

                return redirect()->route('portfolioAdd')->withErrors($validator)->withInput();
            }
            if ($request->hasFile('images')) {  //проверка - загружается ли файл на сервер

                $file = $request->file('images');

                $input['images'] = $file->getClientOriginalName();

                $file->move(public_path() . '/img', $input['images']); //перемещает файл 
                //из временного хранилища в назначенную директорию
            }

            $portfolio = new Portfolio();
            $portfolio->fill($input);

            if ($portfolio->save()) {

                return redirect('admin')->with('status', 'Страница добавлена');
            }

    }

        if (view()->exists('admin.pages_add')) {

            $data = [

                'title' => 'Новая работа в портфолио'
            ];
            return view('admin.portfolio_add', $data);
        }
        abort(404);
}
}
