<?php

namespace App\Http\Controllers;

use App\Car;
use Illuminate\Support\Facades\Request;
use App\Http\Traits\MsgTrait;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    use MsgTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Cars/Index', [
            'filters' => Request::all('search'),
            'cars' => Car
                ::filter(Request::only('search'))
                ->paginate()
                ->transform(function ($car) {
                    return [
                        'id' => $car->id,
                        'name' =>$car->name,
                        'vin' =>$car->vin,
                        'creator' => $car->creator,
                        'updater' => $car->updater,
                        'created_at' => $car->created_at->format('Y.m.d H:i:s'),
                        'updated_at' => $car->updated_at->format('Y.m.d H:i:s'),

                    ];
                }),
        ]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Cars/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $messages = [
            'required' => 'Error. This field is required.',
            'max' => 'Error. The field must contain no more than :max characters',
            'unique' => 'Error. This VIN already exists, create another',
        ];
        $rules = [];
        $rules['name'] = array('required','max:100');
        $rules['vin'] = array('required', 'unique:cars', 'max:17');

        //dd(Request::all());
        $validator = Validator::make(Request::all(), $rules, $messages);
        
        if ($validator->fails()) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }
		try {

            DB::beginTransaction();

			$car = new Car;
            $car->name= strtoupper(Request::get('name'));
            $car->vin= strtoupper(Request::get('vin'));
            $car->image_auction = array_filter(Request::get('image_auction'));
            $car->image_taken_auction = array_filter(Request::get('image_taken_auction'));
            $car->image_in_stock = array_filter(Request::get('image_in_stock'));
            $car->image_delivered = array_filter(Request::get('image_delivered'));
            $car->image_left_to_client = array_filter(Request::get('image_left_to_client'));

            $car->save();
            DB::commit();

		} catch (\Exception $e) {

            DB::rollback();

			$message = $this->msgError() .   $e->getMessage();

			return Redirect::route('cars')->with('error',$message);
		}

		return Redirect::route('cars')->with('success',$this->msgInsert());
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Car $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
        return Inertia::render('Cars/Edit',[
            'car'=> $car,
        ]);
    }

    public function update(Request $request, Car $car)
    {
        $messages = [
            'required' => 'Error. This field is required.',
            'max' => 'Error. The field must contain no more than :max characters',
            'unique' => 'Error. This VIN already exists, create another',
        ];
        $rules = [];
        $rules['name'] = array('required','max:100');
        $rules['vin'] = array('required', Rule::unique('cars')->ignore($car->vin, 'vin'), 'max:17');

        $validator = Validator::make(Request::all(), $rules, $messages);
        
        if ($validator->fails()) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }
		try {

            DB::beginTransaction();

            $car->name= strtoupper(Request::get('name'));
            $car->vin= strtoupper(Request::get('vin'));
            $car->image_auction = array_filter(Request::get('image_auction'));
            $car->image_taken_auction = array_filter(Request::get('image_taken_auction'));
            $car->image_in_stock = array_filter(Request::get('image_in_stock'));
            $car->image_delivered = array_filter(Request::get('image_delivered'));
            $car->image_left_to_client = array_filter(Request::get('image_left_to_client'));

            $car->save();
            DB::commit();

		} catch (\Exception $e) {

            DB::rollback();

			$message = $this->msgError() .   $e->getMessage();

			return Redirect::route('cars')->with('error',$message);
		}

		return Redirect::route('cars')->with('success',$this->msgUpdate());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Car $car
     * @return \Illuminate\Http\Response
     */
    public function destroy(Car $car)
    {
		try{

            DB::beginTransaction();

            $car = Car::findOrFail($car->id);
            $car->delete();

			DB::commit();

            $message = $this->msgDelete();

		} catch(\Exception $e){

			DB::rollback();

            $message = $this->msgError() .   $e->getMessage();

            return Redirect::route('cars')->with('error',$message);

		}

        return Redirect::route('cars')->with('success',$message);

    }
}
