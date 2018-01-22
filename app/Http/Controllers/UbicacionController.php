<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Ubicacion;
use App\Provincia;

class UbicacionController extends Controller
{

    //https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJN5Yawx9XAb4RDAWDZCuyxlc&key=AIzaSyBwWqco7BXjvq5FCCIxqm9goTuOI7U6smI

    //https://maps.googleapis.com/maps/api/place/details/json?placeid=ChIJMck6uHpWAb4RI7lrfZFHv4Q&key=AIzaSyBwWqco7BXjvq5FCCIxqm9goTuOI7U6smI
    public function searchGooglePlace(Request $request){

        $filter = str_replace ( ' ' , '%' , $request->q );
        $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=".$filter."&key=".env('GOOGLE_PLACES_KEY')."&language=es&types=locality";
        $response = file_get_contents($url);
        return response()->json(json_decode($response));
    }
}
