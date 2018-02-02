<?php

use App\Helpers\Googlemaps;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GoogleMapsTest extends TestCase
{
    public function testInitializeGoogleMaps()
    {
        $this->assertInternalType('object', new GoogleMaps());
    }

    public function testCreateMapIsString()
    {
        $this->assertInternalType('string', (new GoogleMaps())->createMap());
        $this->assertContains('<script type="text/javascript">', (new GoogleMaps())->createMap());
        $this->assertContains('map.fitBounds', (new GoogleMaps())->createMap());
    }

    public function testAddMarker()
    {
        $gmap = (new GoogleMaps())->addMarker(['lat' => 10, 'lng' => 20]);

        $this->assertContains('LatLng(10, 20)', $gmap->createMap());
    }

    public function testAddPeticionesMarkers()
    {
        $peticiones = [['acoord_y' => 10, 'acoord_x' => 20, 'empresa' => '', 'tecnico' => '', 'referencia' => '']];
        $gmap = (new GoogleMaps())->addPeticionesMarkers($peticiones);

        $this->assertContains('LatLng(10, 20)', $gmap->createMap());
    }
}
