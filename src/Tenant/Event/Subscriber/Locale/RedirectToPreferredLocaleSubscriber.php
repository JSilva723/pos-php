<?php

declare(strict_types=1);

namespace Tenant\Event\Subscriber\Locale;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use UnexpectedValueException;

use function array_unique;
use function array_unshift;
use function explode;
use function in_array;
use function sprintf;
use function Symfony\Component\String\u;
use function trim;

class RedirectToPreferredLocaleSubscriber implements EventSubscriberInterface
{
    /** @var list<string> The supported locales */
    private array $locales;
    private string $defaultLocale;

    public function __construct(
        private UrlGeneratorInterface $urlGenerator,
        string $locales,
        ?string $defaultLocale = null,
    ) {
        $this->locales = explode('|', trim($locales));

        if (empty($this->locales)) {
            throw new UnexpectedValueException('The list of supported locales must not be empty.');
        }

        $this->defaultLocale = $defaultLocale ?: $this->locales[0];

        if (!in_array($this->defaultLocale, $this->locales, true)) {
            throw new UnexpectedValueException(sprintf('The default locale ("%s") must be one of "%s".', $this->defaultLocale, $locales));
        }

        // Add the default locale at the first position of the array,
        // because Symfony\HttpFoundation\Request::getPreferredLanguage
        // returns the first element when no an appropriate language is found
        array_unshift($this->locales, $this->defaultLocale);
        $this->locales = array_unique($this->locales);
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Ignore sub-requests and all URLs but the homepage
        if (!$event->isMainRequest() || '/' !== $request->getPathInfo()) {
            return;
        }

        // Ignore requests from referrers with the same HTTP host in order to prevent
        // changing language for users who possibly already selected it for this application.
        $referrer = $request->headers->get('referer');

        if (null !== $referrer && u($referrer)->ignoreCase()->startsWith($request->getSchemeAndHttpHost())) {
            return;
        }

        $preferredLanguage = $request->getPreferredLanguage($this->locales);

        if ($preferredLanguage !== $this->defaultLocale) {
            $response = new RedirectResponse($this->urlGenerator->generate('tenant_sale_order_index', ['_locale' => $preferredLanguage]));
            $event->setResponse($response);
        }
    }
}
