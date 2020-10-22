<?php
namespace App\Http\Traits;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Pagination\UrlWindow;
use Illuminate\Support\Facades\Request;

trait FmTrait {

    public function getFolders($directory = 'images')
    {
        $directories = Storage::directories($directory);
        $items = [];
        $i=0;
        foreach ($directories as &$value) {
            while(Str::contains($value, '/'))
            {
                $value = Str::after($value, '/');
            }
            $items[$i]['name'] = $value;
            $items[$i]['type'] = 'folder';
            $i++;
        }

        return $items;
    }

    public function getFiles($directory = 'images')
    {
        $allowedFiles = array('png', 'jpg', 'jpeg','JPG', 'JPEG', 'PNG');
        $files = Storage::files($directory);
        $items = [];
        $i=0;
        
        foreach ($files as &$value) {

            if (preg_match('/\.('.implode('|', $allowedFiles).')$/', $value)) {
                $tempValue= $value;
                $tempValue = Str::replaceFirst('public','',$tempValue);
                $items[$i]['path'] = $tempValue;
                while(Str::contains($value, '/'))
                {
                    $value = Str::after($value, '/');
                }
                $items[$i]['name'] = $value;
                $items[$i]['type'] = 'file';
                $i++;
            }
        }

        return $items;
    }

    public function links($view = null, $data = [], $request, $pagination)
    {
        $this->appends($request->all());

        $window = UrlWindow::make($this);

        $elements = array_filter([
            $window['first'],
            is_array($window['slider']) ? '...' : null,
            $window['slider'],
            is_array($window['last']) ? '...' : null,
            $window['last'],
        ]);

        return Collection::make($elements)->flatMap(function ($item) {
            if (is_array($item)) {
                return Collection::make($item)->map(function ($url, $page) {
                    return [
                        'url' => $url,
                        'label' => $page,
                        'active' => $this->currentPage() === $page,
                    ];
                });
            } else {
                return [
                    [
                        'url' => null,
                        'label' => '...',
                        'active' => false,
                    ],
                ];
            }
        })->prepend([
            'url' => $this->previousPageUrl(),
            'label' => 'Prev',
            'active' => false,
        ])->push([
            'url' => $this->nextPageUrl(),
            'label' => 'Next',
            'active' => false,
        ]);
    }
}