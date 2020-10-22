<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

use App\Car;
use App\Container;

class IndexController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Front/Index');
    }

    public function search()
    {

        $messages = [
            'required_without' => 'Error. At least one field must be filled',
            'max' => 'Error. The field must contain no more than :max characters',
        ];
        
        $validator = Validator::make(Request::all(), [
            'container' => ['required_without:vin','max:11'],
            'vin' => ['required_without:container', 'max:17'],

        ], $messages);

        if ($validator->fails()) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }

        if (Request::filled('container'))
        {
            return redirect()->route('search.container', ['container' => Request::get('container')]);
        }

        if (Request::filled('vin'))
        {
           return redirect()->route('search.car', ['car' => Request::get('vin')]);
        }

    }

    public function searchByContainer($container)
    {
        $container = Container::search($container)->first();

        if ($container) {
            return Inertia::render('Front/SearchContainer', [
                'container' => $container,
            ]);
        }

        return Redirect::back()
        ->withErrors(array('error_empty'=>'Nothing found'))
        ->withInput();

    }

    public function searchByCar($car)
    {
        $car = Car::search($car)->first();

        if ($car) {
            return Inertia::render('Front/SearchCar', [
                'car' => $car,
            ]);
        }

        return Redirect::back()
        ->withErrors(array('error_empty'=>'Nothing found'))
        ->withInput();

    }

}
