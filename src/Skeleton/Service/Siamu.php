<?php

namespace App\Skeleton\Service;

use DateTime;
use Psr\Cache\InvalidArgumentException;
use Psr\Log\LoggerInterface;
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
        private readonly bool                $forceMaintenance,
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface     $logger,
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

        $result = $cache->get('siamu_ws' . $ws, function (ItemInterface $item) use ($ws) {
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

        // Si on est en environnement de dev, on supprime le cache
        if ($_ENV['APP_ENV'] === 'dev') {
            $cache->delete('siamu_ws' . $ws);
        }

        return $result;
    }

    /**
     * Vérifie si le site doit être en mode maintenance.
     * Pour qu'il soit en maintenance, il faut qu'il y ait au moins un évènement de criticité 3
     * Ou que l'on ai défini la variable d'environnement APP_FORCE_MAINTENANCE à true
     * @return bool
     */
    public function isMaintenanceModeRequired(): bool
    {
        // Si on est en environnement de dev, on ne passe pas en maintenance
        if ($_ENV['APP_ENV'] === 'dev') {
            return false;
        }

        if ($this->forceMaintenance) {
            return true;
        }

        // On récupère les 2 web-services
        $wsAppli = $this->getAlertsWsAppli();
        $wsAppliSource = $this->getAlertsWsAppliSource();

        // S'il n'y a pas d'events, on sort tout de suite
        if (empty($wsAppli) && empty($wsAppliSource)) {
            return false;
        }

        // On vérifie la criticité du premier évènement de wsAppli
        $firstEvent = $wsAppli[0];
        if (isset($firstEvent)) {
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $firstEvent['date_debut']);

            if (isset($firstEvent['criticite']) &&
                $firstEvent['criticite'] === '3' &&
                $dateDebut->diff(new DateTime())->format('%R') === '+'
            ) {
                // on redirige vers la page de maintenance
                return true;
            }
        }

        // On vérifie la criticité du premier évènement de wsAppliSource
        $firstEvent = $wsAppliSource[0];
        if (isset($firstEvent)) {
            $dateDebut = DateTime::createFromFormat('Y-m-d H:i:s', $firstEvent['date_debut']);

            if (isset($firstEvent['criticite']) &&
                $firstEvent['criticite'] === '3' &&
                $dateDebut->diff(new DateTime())->format('%R') === '+'
            ) {
                // on redirige vers la page de maintenance
                return true;
            }
        }

        return false;
    }

    public function getAlertsWsAppli(): array
    {
        try {
            return $this->fetchAlert($this->urlApp);
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e->getMessage());
        }
        return [];
    }

    public function getAlertsWsAppliSource(): array
    {
        try {
            return $this->fetchAlert($this->urlAppSource);
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e->getMessage());
        }
        return [];
    }
}