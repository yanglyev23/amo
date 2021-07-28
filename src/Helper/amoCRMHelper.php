<?php
namespace App\Helper;

use AmoCRM\Client\AmoCRMApiClient;
use League\OAuth2\Client\Token\AccessToken;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class amoCRMHelper{
    private $baseDomain;
    private $baseUrl;
    private $clientID;
    private $clientSecret;
    private $code;
    private $redirectUri;
    private $path;
    private $accessToken;
    private $refreshToken;

    public function request($path, $data){
        $url = $this->baseUrl.$path;
        if ($curl = curl_init()) 
        {
            curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl,CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->accessToken
                ));
            curl_setopt($curl,CURLOPT_POST, true);
            curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        }
        else
        {
            "Ошибка";
        }
    }
    
    public function __construct(ParameterBagInterface $params){
        $this->baseDomain = $params->get('amocrm.subdomain');
        $this->baseUrl = "https://{$this->baseDomain}.amocrm.ru/api/v4/";
        $this->clientID = $params->get('amocrm.client_id');
        $this->clientSecret = $params->get('amocrm.client_secret');
        $this->redirectUri = $params->get('amocrm.redirect_uri');
        $this->code = $params->get('amocrm.code');
        $this->path = $params->get('amocrm.token_file');
        $this->refreshToken = $this->getRefreshToken();
    }

    public function getAccessToken(){
        $subdomain = 'test'; //Поддомен нужного аккаунта
        $link = 'https://' . $subdomain . '.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса
        
        /** Соберем данные для запроса */
        $data = [
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret, 
            'grant_type' => 'authorization_code',
            'refresh_token' => $this->refreshToken,
            'redirect_uri' => $this->redirectUri,
        ];
        
        /**
         * Нам необходимо инициировать запрос к серверу.
         * Воспользуемся библиотекой cURL (поставляется в составе PHP).
         * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
         */
        $curl = curl_init(); //Сохраняем дескриптор сеанса cURL
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $link);
        curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
        $code = (int)$code;
        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];
        
        try
        {
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
            }
        }
        catch(\Exception $e)
        {
            die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }
        
        /**
         * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
         * нам придётся перевести ответ в формат, понятный PHP
         */
        $response = json_decode($out, true);
        
        $this->accessToken = $response['access_token']; //Access токен
        return $this->accessToken;
    }

    public function auth(){
        $baseDomain = 'yalos23';
        $link1 = 'https://' . $this->baseDomain . '.amocrm.ru/oauth2/access_token';
        $data = [
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret, 
            'grant_type' => 'authorization_code',
            'code' => $this->code,
            'redirect_uri' => $this->redirectUri,
        ];
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $link1);
        curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
        $code = (int)$code;
        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];
        
        try
        {
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undefined error', $code);
            }
        }
        catch(\Exception $e)
        {
            die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }
        
        $response = json_decode($out, true);
        $this->saveToken($response['refresh_token']);
    }
    public function saveToken($token) {
        $json = json_decode(file_get_contents($this->path));
        $json->refresh_token = $token;
        file_put_contents($this->path, json_encode($json));
    }
    public function getRefreshToken() {
        $json = json_decode(file_get_contents($this->path));
        return $json->refresh_token;
    }
    public function addLead($accessToken){
        $data = [
            ["name" => "sdelka 1", 
            "price" => 2000],
        ];
        $this->request('leads', $data);
    }
    
    public function addContact($accessToken){
        $data = [
            ["name" => "contact 1", 
            "first_name" => "Ivan", 
            "last_name" => "Ivanov"],
        ];
        $this->request('contacts', $data);
    }
}