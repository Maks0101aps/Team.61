<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    /**
     * Get cities by region.
     *
     * @param string $region
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCitiesByRegion($region)
    {
        // Декодируем регион, так как он может быть в URL-encoded формате
        $region = urldecode($region);
        
        // Получаем города по региону из метода в модели Teacher
        $cities = Teacher::getCitiesByRegion($region);
        
        return response()->json([
            'cities' => $cities
        ]);
    }
} 