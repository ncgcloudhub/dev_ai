<?php

namespace App\Http\Controllers;

use App\Services\StableDiffusionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\DalleImageGenerate as ModelsDalleImageGenerate;
use Illuminate\Support\Facades\Log;

class ImageController extends Controller
{
    protected $stableDiffusionService;

    public function __construct(StableDiffusionService $stableDiffusionService)
    {
        $this->stableDiffusionService = $stableDiffusionService;
    }
    
    public function imageIndex()
    {
        $apiKey = config('services.stable_diffusion.api_key');
        return view('backend.image_generate.images_sd_d',compact('apiKey'));
    }
    



    
}
