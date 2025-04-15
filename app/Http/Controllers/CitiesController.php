<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;

class CitiesController extends Controller
{
    /**
     * List all regions and cities
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $regions = Teacher::getRegions();
        
        return response()->json([
            'regions' => $regions
        ]);
    }
    
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
    
    /**
     * Public endpoint for getting cities by region
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function publicIndex()
    {
        $regions = Teacher::getRegions();
        
        return response()->json([
            'regions' => $regions
        ]);
    }
} 