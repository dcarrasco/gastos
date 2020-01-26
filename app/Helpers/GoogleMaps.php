<?php

namespace App\Helpers;

class Googlemaps
{

    /**
     * Arreglo que contiene los marcadores de posicion
     *
     * @var array
     */
    protected $markers = [];

    /**
     * Identificador del DIV que contendrá el mapa
     *
     * @var string
     */
    protected $mapId = 'canvas_gmap';

    /**
     * Texto CSS para dar estilo a DIV del mapa
     *
     * @var string
     */
    protected $mapCss = 'height: 100px';

    /**
     * Zoom del mapa
     *
     * @var integer
     */
    protected $mapZoom = 15;

    /**
     * Texto javascript a escribir
     *
     * @var string
     */
    protected $txtJs = '';

    /**
     * URL de la API
     *
     * @var string
     */
    protected $urlJs = 'https://maps.googleapis.com/maps/api/js';

    /**
     * Llave de la API
     *
     * @var string
     */
    protected $apiKey = 'AIzaSyDLt78yzJgaZAZNaFNvJ4RbD9pY2hSTTY0';

    // --------------------------------------------------------------------

    /**
     * Constructor
     *
     * @return  void
     **/
    public function __construct($config = [])
    {
        $this->initialize($config);
    }

    // --------------------------------------------------------------------

    /**
     * Inicializa el módulo
     *
     * @param  array $config Arreglo con la configuración del módulo
     * @return void
     */
    public function initialize($config)
    {
        foreach ($config as $config_key => $config_value) {
            $this->{$config_key} = $config_value;
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Crea mapa
     *
     * @return void
     */
    public function createMap()
    {
        $functionName = "initMap_{$this->mapId}";

        $urlParam = [
            'key'      => $this->apiKey,
            'callback' => $functionName,
        ];
        $urlJs = $this->urlJs . '?' . http_build_query($urlParam);


        return "<div id=\"{$this->mapId}\" style=\"{$this->mapCss}\"></div>\n"
            . "<script type=\"text/javascript\">\n"
            . "function {$functionName}() {\n"
            . "var map = new google.maps.Map(document.getElementById('{$this->mapId}'), "
            . "{center: {lat:0, lng:0}, zoom: {$this->mapZoom}})\n"
            . "var bounds = new google.maps.LatLngBounds()\n"
            . $this->txtJs
            . (count($this->markers) === 1 ? "map.setCenter(ubic_1)\n" : "map.fitBounds(bounds)\n")
            . "}\n"
            . "</script>\n"
            . "<script type=\"text/javascript\" src=\"{$urlJs}\" defer async></script>\n";
    }

    // --------------------------------------------------------------------

    /**
     * Agrega marcadores desde un arreglo de peticiones TOA
     *
     * @param array $peticiones Arreglo de peticiones
     * @return $this
     */
    public function addPeticionesMarkers($peticiones)
    {
        collect($peticiones)->each(function ($peticion) {
            $this->addMarker([
                'lat'   => $peticion['acoord_y'],
                'lng'   => $peticion['acoord_x'],
                'title' => "{$peticion['empresa']} - {$peticion['tecnico']} - {$peticion['referencia']}",
            ]);
        });

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Agrega un marcador
     *
     * @param  array $marker Definición del marcador
     * @return void
     */
    public function addMarker($marker = null)
    {
        $markerConfig = [
            'lat'    => 0,
            'lng'    => 0,
            'map'    => 'map',
            'title'  => 'title',
            'zindex' => 100,
        ];

        foreach ($markerConfig as $marker_key => $marker_value) {
            if (array_key_exists($marker_key, $marker)) {
                $markerConfig[$marker_key] = $marker[$marker_key];
            }
        }

        if (
            $markerConfig['lat'] !== 0
            and $markerConfig['lng'] !== 0
            and $markerConfig['lat'] !== $markerConfig['lng']
        ) {
            array_push($this->markers, $markerConfig);
            $nMarker = count($this->markers);

            $this->txtJs .= "var ubic_{$nMarker} = "
                . "new google.maps.LatLng({$markerConfig['lat']}, {$markerConfig['lng']});\n"
                . "var marker_{$nMarker} = "
                . "new google.maps.Marker({position: ubic_{$nMarker}, title: '{$markerConfig['title']}'});\n"
                . "marker_{$nMarker}.setMap(map);\n"
                . "bounds.extend(marker_{$nMarker}.position);\n\n";
        }

        return $this;
    }
}
