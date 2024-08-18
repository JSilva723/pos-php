<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class IndexController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    public function lang(Request $request, string $lang, RouterInterface $router): RedirectResponse
    {
        $referer = $request->headers->get('referer');

        if ($referer) {
            $routeInfo = $router->match(parse_url($referer, PHP_URL_PATH));
            $routeName = $routeInfo['_route'];
            $routeParams = array_filter($routeInfo, function ($key) {
                return strpos($key, '_') !== 0;
            }, ARRAY_FILTER_USE_KEY);

            $routeParams += ['_locale' => $lang];

            return $this->redirectToRoute($routeName, $routeParams);
        }

        return $this->redirectToRoute('app_index', ['_locale' => $lang]);
    }
}
