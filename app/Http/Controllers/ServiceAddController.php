<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Service;

class ServiceAddController extends Controller
{
    //
    public function execute(Request $request)
    {

        if ($request->isMethod('post')) {
            $input = $request->except('_token');

            $messages = [

                'required' => 'Поле :attribute обязательно к заполнению',
                'unique' => 'Поле :attribute должно быть уникальным'
            ];

            $validator = Validator::make($input, [

                'name' => 'required|max:255',
                'text' => 'required|max:255'

            ], $messages);

            if ($validator->fails()) {

                return redirect()->route('serviceAdd')->withErrors($validator)->withInput();
            }
            
            $service = new Service();
            $service->fill($input);

            if ($service->save()) {

                return redirect('admin')->with('status', 'Страница добавлена');
            }
        }

        if (view()->exists('admin.service_add')) {

            $data = [

                'title' => 'Новый сервис'
            ];
            return view('admin.service_add', $data);
        }
        abort(404);
    }
}
