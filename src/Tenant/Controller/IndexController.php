<?php

declare(strict_types=1);

namespace Tenant\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

use function array_filter;
use function parse_url;
use function str_starts_with;

use const ARRAY_FILTER_USE_KEY;
use const PHP_URL_PATH;

class IndexController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('index/index.html.twig');
    }

    public function lang(Request $request, string $lang, RouterInterface $router): RedirectResponse
    {
        $referer = $request->headers->get('referer');

        if ($referer) {
            $routeInfo = $router->match((string) parse_url($referer, PHP_URL_PATH));
            $routeName = $routeInfo['_route'];
            $routeParams = array_filter($routeInfo, function ($key) {
                return !str_starts_with($key, '_');
            }, ARRAY_FILTER_USE_KEY);

            $routeParams += ['_locale' => $lang];

            return $this->redirectToRoute($routeName, $routeParams);
        }

        return $this->redirectToRoute('tenant_index', ['_locale' => $lang]);
    }
}
