<?php
 
    namespace App\Service;

    use Symfony\Contracts\HttpClient\HttpClientInterface;
 

class apiTiempo 
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client=$client;
    }

    
    public function getTiempoJaen()
    {
        $response=$this->client->request(
            'GET',
            'https://www.el-tiempo.net/api/json/v2/provincias/23/municipios'
        );

        $content=$response->getContent();
    

        return $content;
    }

}