<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\FmTrait;
use Spatie\Image\Image;
use Spatie\Image\Manipulations;
use Illuminate\Support\Str;
use Zip;

class FilemanagerController extends Controller
{
  use FmTrait;

    public function upload(Request $request)
    {
      $directory = '';

      if ($request->has('directory')) {
        $directory = Str::finish($request->directory, '/');
      }

      if( $this->optimizationFile($request->file('sFilename'), $directory) ) {
        return response()->json([
            'success' => true,
            'dir' => $directory,
        ], 200);
      }

    }

    public function uploadZip(Request $request)
    {
      $directory = '';
      $uploadedFile = $request->file('zipFilename');

      if ($request->has('directory')) {
        $directory = Str::finish($request->directory, '/');
      }

      $filename = $uploadedFile->getClientOriginalName();

      if (Storage::disk('public')->putFileAs(
        $directory,
        $uploadedFile,
        $filename
      )) {
        return response()->json([
            'success' => true,
        ], 200);
      }

    }

    public function unZip(Request $request)
    {

      $zipFile = $request->zipFilename;
      $directory = $request->type ?? 'cars';

      $zip = Zip::open('./images/zips/'.$zipFile);

      $zipFiles = $zip->listFiles();
      $zip->setMask(0777);

      if ($zip->extract(Str::start($directory, 'images/'))) {

        $zip->close();

        return response()->json([
            'success' => true,
            'zipFiles' => $zipFiles,
        ], 200);
      }

    }

    public function optimizationFile($uploadedFile, $directory)
    {
      
      $filename = $uploadedFile->getClientOriginalName();

      return Image::load($uploadedFile)
          ->fit(Manipulations::FIT_FILL, 900, 675)
          ->optimize()
          ->save('images/'.$directory.$filename);
    }

    public function optimizationFiles(Request $request)
    {
      $listFiles = Str::of($request->listFiles)->explode(',');
      $directory = Str::finish($request->type ?? 'cars', '/');
      $total = count($listFiles);
      $processed = 0;
      $files = []; 

      foreach($listFiles as $fileItem) {
        if (Storage::disk('public')->exists($directory . $fileItem)) {

          Image::load('images/'.$directory . $fileItem)
          ->fit(Manipulations::FIT_FILL, 900, 675)
          ->optimize()
          ->save('images/'.$directory . $fileItem);

          array_push($files, '/images/'. $directory . $fileItem);
          $processed ++;
        }
      }
      return response()->json([
        'success' => true,
        'files' => $files,
        'total' => $total,
        'processed' => $processed,
      ], 200);

    }

    public function deleteZip(Request $request)
    {
      if (Storage::disk('public')->delete('zips/'.$request->filename)) {
        return response()->json(['success' => true,], 200);
      }
    }

    public function index(Request $request)
    {

        $directory = 'public/images';
        $url = 'admin/fm/index';
        if ($request->has('directory')) {
          $directory = $directory . '/'.$request->directory;
        }

        $folderItems = $this->getFolders($directory);
        $fileItems = $this->getFiles($directory);

        $collection = collect($folderItems);
        $countFolder = $collection->count();
        $collection = $collection->concat($fileItems);

        $page = (int) $request->input('page') ?: 1;
        $onPage = 12 - $countFolder;

        $slice = $collection->slice(($page-1)* $onPage, $onPage);

        $paginator = new \Illuminate\Pagination\LengthAwarePaginator($slice, $collection->count(), $onPage);
        $paginator->withPath(url($url));
        $paginator->appends(request()->query());

        return response()->json([
            'success' => true,
            'items' => $paginator,
            'req' => $request->all(),
            'tets'=> request(),
        ], 200);

    }

}
