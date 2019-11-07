<?php

namespace Middleware;

use Psr\Http\Message\ServerRequestInterface as Request,
    Psr\Http\Message\ResponseInterface as Response,
    Psr\Container\ContainerInterface as Container;

class Poke_Middleware {

    protected $cContainer;

    public function __construct (Container $cContainer) { $this -> cContainer = $cContainer; }
    public function __invoke (Request $rRequest, Response $rResponse, $cNext) {

    $rResponse -> getBody() -> write('BEFORE ');

    $rResponse = $cNext($rRequest, $rResponse);

    $rResponse -> getBody() -> write(' AFTER');

    return $rResponse;

  }

}
