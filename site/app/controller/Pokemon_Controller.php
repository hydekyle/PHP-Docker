<?php

namespace Controller;

use Psr\Container\ContainerInterface as Container,
    Psr\Http\Message\ServerRequestInterface as Request,
    Psr\Http\Message\ResponseInterface as Response,

    Model\Pokemon_Model;

class Pokemon_Controller {

  protected $cContainer;
  protected $strImages;

  public function __construct (Container $cContainer) { 
    //$this -> strImages = obtener la ruta de las imagenes carpeta
    $this -> cContainer = $cContainer; 
  }

  // public function getall (Request $rRequest, Response $rResponse) {

  //   $aConfig = $this -> cContainer -> get('config');

  //   $aData = ($aConfig['db']['driver'] == 'json') ? json_decode(file_get_contents($this -> cContainer -> db['path'] . '/' . $this -> cContainer -> db['filename']), true) : $this -> cContainer -> db -> table('pokemons') -> get();

  //   $aParameters = [
  //     'aPage' =>  [
  //       'strTitle' => 'Welcome - Slim + Twig',
  //       'strDescription' => 'Welcome to the oficial page Slim + Twig.',
  //       'strType' => 'Controller'
  //     ],
  //     'aPokemons' => $aData
  //   ];

  //   return $this -> cContainer -> view -> render($rResponse, 'pokemons.twig', $aParameters);

  // }

  protected function get_png_route($id){
    $aConfig = $this -> cContainer -> get('config');
    $ruta = $aConfig['pokeimg']['path'];
    $ruta .= "/";
    switch ($id){
      case $id < 10: $ruta .= "00$id"; break;
      case $id < 100: $ruta .= "0$id"; break;
      default: $ruta .= $id;
    }
    $ruta .= "." . $aConfig['pokeimg']['driver'];
    
    return $ruta;
  }

  protected function show_pokemon($poke){
    $strTypes = '';
    //$typesAmount = count($poke['types']);
    foreach($poke['types'] as $type) 
      $strTypes .= " $type +";
    $strHTML = '';
    $strHTML .= '<strong>ID: </strong>' . $poke['id'] . '<br>';
    $strHTML .= '<strong>Nombre: </strong>' . $poke['name'] . '<br>';
    $strHTML .= '<strong>TIPO: </strong>' . substr($strTypes, 0, -1) . '<br>';
  
    echo $strHTML;
  }

  protected function get_pokemon($id){
    $pokemons = json_decode(file_get_contents($this -> cContainer -> db['path'] . '/' . $this -> cContainer -> db['filename']), true);
    $pokeID = $id - 1;
    if ($pokeID >= 0 && $pokeID < count($pokemons)) 
      return $pokemons[$pokeID];
    else 
      return null;
  }

  public function get_by_id(Request $rRequest, Response $rResponse, $aArgs){
    $pokemons = json_decode(file_get_contents($this -> cContainer -> db['path'] . '/' . $this -> cContainer -> db['filename']), true);
    $pokeID = $aArgs['id'] - 1;
    if ($pokeID >= 0 && $pokeID < count($pokemons)) 
      $this -> show_pokemon($pokemons[$pokeID]);
    else 
      echo "El pokémon " . $aArgs['id'] . " no se encuentra en la pokédex.";
  }

  public function get_by_name(Request $rRequest, Response $rResponse, $aArgs){
    $pokemons = json_decode(file_get_contents($this -> cContainer -> db['path'] . '/' . $this -> cContainer-> db['filename']), true);
    $name = strtolower($aArgs['name']);
    $my_array = []; //Rehago el array para que la clave sea el nombre y no el id.
    foreach ($pokemons as $poke) 
      $my_array[$poke['name']] = $poke;
    if ($my_array[$name] != null) 
      $this -> show_pokemon($my_array[$name]);
    else 
      echo "El pokémon " . $aArgs['name'] . " no ha sido encontrado.";
  }

  public function twig_get_by_id(Request $rRequest, Response $rResponse, $aArgs){
    $aData = $this -> cContainer -> db -> table('pokemons') -> get();
    $pokeID = $aArgs['id'];
    $pokemon = $aData[$pokeID - 1];
    //d($pokemon -> types);
    if ($pokemon != null){
      $aParameters = [
        'aPage' =>  [
          'poke_id' => $pokeID,
          'poke_name' => $pokemon -> name,
          'poke_types' => $pokemon -> types,
          'img_route' => $this -> get_png_route($pokeID),
          'db_data' => $aData
        ]
      ];
      
      return $this -> cContainer -> view -> render($rResponse, 'pokedex.twig', $aParameters);
    } 
    else
      return "Pokémon no encontrado";
  }
  
}
