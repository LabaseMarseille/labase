<?php

namespace App\Service;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Siamu
{
    public function __construct(
        private readonly string              $urlSiamu,
        private readonly string              $urlApp,
        private readonly string              $urlAppSource,
        private readonly string              $idApp,
        private readonly string              $current,
        private readonly HttpClientInterface $client
    )
    {
    }

    /**
     * @param string $ws
     * @return array
     * @throws InvalidArgumentException
     */
    private function fetchAlert(string $ws): array
    {
        $cache = new FilesystemAdapter();

        return $cache->get('siamu_ws' . $ws, function (ItemInterface $item) use ($ws) {
            $response = $this->client->request(
                'GET',
                $this->urlSiamu . $ws,
                [
                    'query' => [
                        'id' => $this->idApp,
                        'current' => $this->current,
                    ],
                ],
            );

            $content = $response->toArray();

            // S'il y a une alerte, on met en cache pendant 5 minutes
            // Sinon, on met en cache pendant 1 heure
            if (sizeof($content) > 0) {
                $item->expiresAfter(300);
            } else {
                $item->expiresAfter(3600);
            }

            return $content;
        });
    }

    /**
     * @return bool
     */
    public function isMaintenanceModeRequired(): bool
    {
        // On récupère les 2 web-services
        $wsAppli = $this->getAlertsWsAppli();
        $wsAppliSource = $this->getAlertsWsAppliSource();

        // S'il y a des events, on vérifie la criticité
        if (sizeof($wsAppli) > 0 || sizeof($wsAppliSource) > 0) {

            // On vérifie la criticité du premier évènement de wsAppli
            if (array_key_exists(0, $wsAppli)) {
                $firstEvent = $wsAppli[0];
                if (array_key_exists('criticite', $firstEvent) && $firstEvent['criticite'] === '3') {
                    // on redirige vers la page de maintenance
                    return true;
                }
            }

            // On vérifie la criticité du premier évènement de wsAppliSource
            if (array_key_exists(0, $wsAppliSource)) {
                $firstEvent = $wsAppliSource[0];
                if (array_key_exists('criticite', $firstEvent) && $firstEvent['criticite'] === '3') {
                    // on redirige vers la page de maintenance
                    return true;
                }
            }
        }

        return false;
    }

    public function getAlertsWsAppli(): array
    {
        try {
            return $this->fetchAlert($this->urlApp);
        } catch (InvalidArgumentException $e) {
            // @todo logger l'erreur
        }
        return [];
    }

    public function getAlertsWsAppliSource(): array
    {
        try {
            return $this->fetchAlert($this->urlAppSource);
        } catch (InvalidArgumentException $e) {
            // @todo logger l'erreur
        }
        return [];
    }
}