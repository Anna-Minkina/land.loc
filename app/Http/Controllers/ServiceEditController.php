<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;
use Illuminate\Support\Facades\Validator;

class ServiceEditController extends Controller
{
    //
    public function execute(Service $service, Request $request)
    {

        if ($request->isMethod('delete')) {
            $service->delete();
            return redirect('admin')->with('status', 'Страница удалена');
        }

        if ($request->isMethod('post')) {
            $input = $request->except('_token');
            $validator = Validator::make($input, [

                'name' => 'required|max:255',
                'text' => 'required|max:255'

            ]);

            if ($validator->fails()) {

                return redirect()
                    ->route('serviceEdit', ['service' => $input['id']])
                    ->withErrors($validator);
            }
            
            $service->fill($input);

            if ($service->update()) {

                return redirect('admin')->with('status', 'Страница обновлена');
            }
        }

        $old = $service->toArray();
        if (view()->exists('admin.service_edit')) {

            $data = [
                'title' => 'Редактирование сервиса - ' . $old['name'],
                'data' => $old
            ];

            return view('admin.service_edit', $data);
        }
    }
}
