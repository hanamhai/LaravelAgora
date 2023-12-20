<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index(){
        $client = new Client();

        $response = $client->get('https://maps.googleapis.com/maps/api/geocode/json?address=https://maps.app.goo.gl/HXKKog7jqqNdnzDbA');

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody(), true);
            dd($data);
            $latitude = $data['results'][0]['geometry']['location']['lat'];
            $longitude = $data['results'][0]['geometry']['location']['lng'];
        } else {
            $latitude = null;
            $longitude = null;
        }
        return view('location.index', compact('latitude', 'longitude'));
    }
}
