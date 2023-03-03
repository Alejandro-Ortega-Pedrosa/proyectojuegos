<?php
namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Psr\Log\LoggerInterface;

class BotTelegram

{
    //cAMPOS DE TELEGRAM
    private $token;
    private $urlstart;
    private $urlend;
    private $chatid;

    private $logger;

    public function __construct($token, $urlstart, $urlend, $chatid, LoggerInterface $logger)
    {
        $this->token = $token;
        $this->urlstart = $urlstart;
        $this->urlend = $urlend;
        $this->chatid = $chatid;
        $this->logger = $logger;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getUrlstart()
    {
        return $this->urlstart;
    }

    public function getUrlend()
    {
        return $this->urlend;
    }

    public function getChatid()
    {
        return $this->chatid;
    }

    public function getFullurl()
    {
        $token = $this->getToken();
        $urlstart = $this->getUrlstart();
        $urlend = $this->getUrlend();

        return $urlstart.$token.$urlend;
    }

    public function main(string $evento)
    {
        //COGE EL ID DEL SERVICES.YAML
        $chatid = $this->getChatid();

        //PARA MANDARSELO A UN USUARIO EN CONCRETO PASANDO EL USUARIO A LA FUNCION
        //$chatid = $user->ggetIdTelegram();

        $content = array (
            'headers' => array("Content-Type" => "application/x-www-form-urlencoded"),
            "body"  => array("chat_id" => $chatid, "text" => "Has sido invitado al evento ".$evento),
        );
       
        $url = $this->getFullurl();
        $httpClient = HttpClient::create();
        
        try {
            $response = $httpClient->request('GET', $url, $content);
            $content = $response->getContent();
            $this->logger->info($content);
        }
        catch (\Exception $e) {
            $this->logger->critical($e);
        }
        
        return $content;
    }
}