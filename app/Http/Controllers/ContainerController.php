<?php

namespace App\Http\Controllers;

use App\Car;
use App\Container;
use Illuminate\Support\Facades\Request;
use App\Http\Traits\MsgTrait;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;

class ContainerController extends Controller
{
    use MsgTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Containers/Index', [
            'filters' => Request::all('search'),
            'containers' => Container
                ::filter(Request::only('search'))
                ->paginate()
                ->transform(function ($container) {
                    return [
                        'id' => $container->id,
                        'name' =>$container->name,
                        'creator' => $container->creator,
                        'updater' => $container->updater,
                        'created_at' => $container->created_at->format('Y.m.d H:i:s'),
                        'updated_at' => $container->updated_at->format('Y.m.d H:i:s'),

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
        return Inertia::render('Containers/Create');
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
        $rules['name'] = array('required','max:11');

        $validator = Validator::make(Request::all(), $rules, $messages);
        
        if ($validator->fails()) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }
		try {

            DB::beginTransaction();

			$container = new Container;
            $container->name= strtoupper(Request::get('name'));
            $container->image_download= array_filter(Request::get('image_download'));
            $container->image_upload= array_filter(Request::get('image_upload'));
            $container->save();

            DB::commit();

		} catch (\Exception $e) {

            DB::rollback();

			$message = $this->msgError() .   $e->getMessage();

			return Redirect::route('containers')->with('error',$message);
		}

		return Redirect::route('containers')->with('success',$this->msgInsert());
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function edit(Container $container)
    {
        return Inertia::render('Containers/Edit',[
            'container'=> $container,
        ]);
    }
    public function update(Request $request, Container $container)
    {
        $messages = [
            'required' => 'Error. This field is required.',
            'max' => 'Error. The field must contain no more than :max characters',
            'unique' => 'Error. This VIN already exists, create another',
        ];
        
        $rules = [];
        $rules['name'] = array('required','max:11');

        $validator = Validator::make(Request::all(), $rules, $messages);
        
        if ($validator->fails()) {
            return Redirect::back()
                        ->withErrors($validator)
                        ->withInput();
        }
		try {

            DB::beginTransaction();

            $container->name= strtoupper(Request::get('name'));
            $container->image_download= array_filter(Request::get('image_download'));
            $container->image_upload= array_filter(Request::get('image_upload'));            
            $container->save();

            DB::commit();

		} catch (\Exception $e) {

            DB::rollback();

			$message = $this->msgError() .   $e->getMessage();

			return Redirect::route('containers')->with('error',$message);
		}

		return Redirect::route('containers')->with('success',$this->msgUpdate());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Container  $container
     * @return \Illuminate\Http\Response
     */
    public function destroy(Container $container)
    {
		try{

            DB::beginTransaction();

            $container = Container::findOrFail($container->id);

            $container->delete();

			DB::commit();

            $message = $this->msgDelete();

		} catch(\Exception $e){

			DB::rollback();

            $message = $this->msgError() .   $e->getMessage();

            return Redirect::route('containers')->with('error',$message);

		}

        return Redirect::route('containers')->with('success',$message);

    }
}
