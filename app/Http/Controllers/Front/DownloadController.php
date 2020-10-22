<?php

namespace App\Http\Controllers\Front;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Zip;
use App\Car;
use App\Container;


class DownloadController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke($model, $field)
    {
        $zip_file = $model . '_' . $field . '.zip';
        $zip = Zip::create($zip_file);
        $car = Car::searchFull($model)->first();
        
        if($car && $car->$field) {

            $images = $car->$field;
             foreach($images as $image) {

                if (Str::startsWith($image, '/')) {
                    $image= Str::replaceFirst('/', '', $image);
                }
                if ($image) {
                    $zip->add($image);
                }
            }

            $zip->close();

            $headers = array(
                'Content-Type' => 'application/octet-stream',
            );
    
            if (file_exists($zip_file)) {
                return Response::download($zip_file,$zip_file, $headers);        
            }            
        } else {

            $container = Container::search($model)->first();

            if($container && $container->$field) {

                $images = $container->$field;
                 foreach($images as $image) {
    
                    if (Str::startsWith($image, '/')) {
                        $image= Str::replaceFirst('/', '', $image);
                    }
                    if ($image) {
                        $zip->add($image);
                    }
                }
    
                $zip->close();
    
                $headers = array(
                    'Content-Type' => 'application/octet-stream',
                );
        
                if (file_exists($zip_file)) {
                    return Response::download($zip_file,$zip_file, $headers);        
                }            
            }            
        }
        
    }

  

}
