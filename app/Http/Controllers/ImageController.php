<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImageService;

class ImageController extends Controller
{
    public $image;

    public function __construct(ImageService $image)
    {
        $this->image = $image;
    }

    public function upload(Request $request)
    {
        return $this->image->upload($request);
    }

    public function move ($from, $to)
    {
        return $this->image->move($from, $to);
    }

    public function remove(Request $request)
    {
        return $this->image->remove($request);
    }
}
