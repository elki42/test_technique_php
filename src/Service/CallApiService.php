<?php

namespace App\Service;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;


class CallApiService
{
    private $client;
    private $params;

    /**
     * Constructeur du service
     * 
     * @param $client Injection de dépendance pour le client HTTP
     * @param $param Injection de dépendance pour les paramètres des appels API.
     */
    public function __construct(HttpClientInterface $client, ParameterBagInterface $params) {
        $this->client = $client;
        $this->params = $params;
    }
    
    /**
     * Récupère le token pour continuer les requêtes vers l'API
     * 
     * @return string Retourne le Token pour l'Authentification
     */
    public function getAuthToken(): string
    {
        $response = $this->client->request('POST', $this->params->get('urlAuth'), [
            'query' => [
                "realm" => '/partenaire',
            ],
            'headers' => [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ],
            'body' => [
                'grant_type' => $this->params->get('grant_type'),
                'client_id' => $this->params->get('client_id'),
                'client_secret' => $this->params->get('client_secret'),
                'scope' => $this->params->get('scope')
            ]
        ]);
        return $response->toArray()['access_token'];
    }

    /**
     * Retourne la liste des offres trouvées pour Bordeaux (les 10 premières)
     * 
     * @return array La liste des offres sous la forme d'un tableau qui va être parcouru par le template twig en suivant
     */
    public function getListeOffres(): array
    {
        $accessToken = $this->getAuthToken();
        $response = $this->client->request('GET', $this->params->get('urlOffre'), ["auth_bearer" => $accessToken]);
        return $response->toArray()['resultats'];
    }
}
