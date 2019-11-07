<?php

namespace Controller;

use Psr\Container\ContainerInterface as Container,

    Psr\Http\Message\ServerRequestInterface as Request,
    Psr\Http\Message\ResponseInterface as Response,

    App\Models\Pokemon_Model;

class Pokemon_Controller {

  protected $cContainer;

  public function __construct (Container $cContainer) { $this -> cContainer = $cContainer; }

  public function getall (Request $rRequest, Response $rResponse) {

    $aConfig = $this -> cContainer -> get('config');

    $aData = ($aConfig['db']['driver'] == 'json') ? json_decode(file_get_contents($this -> cContainer -> db['path'] . '/' . $this -> cContainer -> db['filename']), true) : $this -> cContainer -> db -> table('pokemons') -> get();

    $aParameters = [
      'aPage' =>  [
        'strTitle' => 'Welcome - Slim + Twig',
        'strDescription' => 'Welcome to the oficial page Slim + Twig.',
        'strType' => 'Controller'
      ],
      'aPokemons' => $aData
    ];

    return $this -> cContainer -> view -> render($rResponse, 'pokemons.twig', $aParameters);

  }

  protected function show_pokemon($poke){
    $strTypes = '';
    $typesAmount = count($poke['types']);
    foreach($poke['types'] as $type) 
      $strTypes .= " $type +";
    $strHTML = '';
    $strHTML .= '<strong>ID: </strong>' . $poke['id'] . '<br>';
    $strHTML .= '<strong>Nombre: </strong>' . $poke['name'] . '<br>';
    $strHTML .= '<strong>TIPO: </strong>' . substr($strTypes, 0, -1) . '<br>';
  
    echo $strHTML;
  }

  public function get_pokemon($id){
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
    $pokeID = $aArgs['id'];
    $pokemon = $this -> get_pokemon($pokeID);
    if ($pokemon != null){
      $aParameters = [
        'aPage' =>  [
          'poke_id' => $pokeID,
          'poke_name' => $pokemon['name']
        ]
      ];
      return $this -> cContainer -> view -> render($rResponse, 'pokedex.twig', $aParameters);
    }  
  }
  
}
