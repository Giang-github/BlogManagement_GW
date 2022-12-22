<?php
namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface {
   private $router;

   public function __construct(RouterInterface $routerInterface)
   {
      // $this->session = $sessionInterface;

      $this->router = $routerInterface;
   }

   public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
   {
       //gửi error message về trang base (layout)
      //  $this->session->getFlashBag()->add("Warning","Access Denied !");
       //redirect về trang login (homepage)
       return new RedirectResponse($this->router->generate('app_login'));
    }
}
?>