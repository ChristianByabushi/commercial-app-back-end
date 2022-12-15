<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;




class Options implements FilterInterface
{
  public function before(RequestInterface $request, $arguments = NULL)
  {
  }
  //--------------------------------------------------------------------
  public function after(RequestInterface $request, ResponseInterface $response, $arguments = NULL)
  {
    // Do something here
  }
}
