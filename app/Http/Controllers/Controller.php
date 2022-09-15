<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use GuzzleHttp\Client;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function obtenerCursos()
    {
        $client = new Client();
      //  dd(env('API_KEY'), env('API_ENDPOINT'));

        $resultado = $client->request('GET', env('API_ENDPOINT'), [
            'query' => [
                'wstoken' => env('API_KEY'),
                'wsfunction' => 'core_course_get_courses',
                'moodlewsrestformat' => 'json'
            ]
        ]);


       //

        $listJsonResult = json_decode($resultado->getBody()->getContents());
        $nuevalista = [];

        foreach ($listJsonResult as $item) {
            array_push($nuevalista, [
                'id' => $item->id,
                'shortname' => $item->shortname,
                'fullname' => $item->fullname,
                'visible' => $item->visible
            ]);
        }
        $listJsonResult = null;

        usort($nuevalista, function($a, $b)
        {
            return strtolower($a['fullname']) > strtolower($b['fullname']);
        });

        return $nuevalista;
    }
}
