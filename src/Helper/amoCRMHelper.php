<?php
namespace App\Helper;

use AmoCRM\Client\AmoCRMApiClient;
use League\OAuth2\Client\Token\AccessToken;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;

class amoCRMHelper{
    private $baseDomain;
    public function __construct(){
        $this->baseDomain = 'yalos23';
    }
    public function auth(){
        $baseDomain = 'yalos23';
        $link1 = 'https://' . $this->baseDomain . '.amocrm.ru/oauth2/access_token';
        $data = [
            'client_id' => 'bef135f0-b7a2-4314-9455-47cb30cf3c6f',
            'client_secret' => 'IFjiT5xKorE0VAaZjX3wnKxngMVcJI9fE4gw3IwmgfEVVdrqoIZANHTg5fhGkVxL',
            'grant_type' => 'authorization_code',
            'code' => 'def5020054da39f6c188b35af878b690062b091d726bd6a231dfb29407d929c6950124d70cc8af88c2e70562e9b0c557a86ad17f2bd7c9873f1305e92ff822c8f9bb3b1a85097298d21b6db107a40547d21f31f8f2acb8304482d7b3ba2a7bc6711ba7c467ce46cfe5901f8e28c716e6f5d63b4f720f40e2c0618db2b487cf0fc8c43df2404b1bfc51203bfc08e723528777caa5b76fda8a49d1bb20b3e962d1701691466d90cd7e487e79390843425d44355314fc7d95dac03b52a3efc98c5114a7ab749cea42462705d12c254493a1ce879371856798a59dd64a66b4aeeffb823af9d9df137a9a7d0eb9ea2ba7c8f0ddc333ef6e90d4a082fb453e3cc3513d1476da3fcaa3e2c67013fb1be0e6af3ffeb8eee6f1cc4f5197435754b193cb40e31cfd464f398a783a2065fce262650ecfeaeac53052e5fec57761de2ebbf49278dc36cb1ba6507a2c5aa5e4cb517e59f64b3c564ece12749e7b2062a7f38e3ab40ed8094157fbe555214c8e15577ffe42af1112fa948473efefa648351a01093f44ee3f039a16914a55c21f265689710959594c4970c21f054c3f0114503492b45f0c3f51624dbf4d50e9b67197456a192e41f74d615694b6fafd7514eec5de1c5bf619247b6132fe',
            'redirect_uri' => 'https://53bf987f4957.ngrok.io/',
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
        
        $accessToken = $response['access_token']; //Access токен
        $refreshToken = $response['refresh_token']; //Refresh токен
        $tokentype = $response['token_type']; //Тип токена
        $expiresin = $response['expires_in']; //Через сколько действие токена истекает
        return $accessToken;
    }

    public function addLead($accessToken){
        $baseDomain = 'yalos23';
        $link2 = 'https://' . $this->baseDomain . '.amocrm.ru/api/v4/leads';
        $data = [
            ["name" => "sdelka 1", 
            "price" => 2000],
        ];
        $myCurl = curl_init();
        curl_setopt($myCurl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($myCurl, CURLOPT_URL, $link2);
        curl_setopt($myCurl,CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
            ));
        curl_setopt($myCurl,CURLOPT_POST, true);
        curl_setopt($myCurl,CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($myCurl);
        curl_close($myCurl);

        return $response;
    }
    
    public function addContact($accessToken){
        
        $link3 = 'https://' . $this->baseDomain . '.amocrm.ru/api/v4/contacts';
        $data = [
            ["name" => "contact 1", 
            "first_name" => "Ivan", 
            "last_name" => "Ivanov"],
        ];
        $myCurl = curl_init();
        curl_setopt($myCurl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($myCurl, CURLOPT_URL, $link3);
        curl_setopt($myCurl,CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $accessToken
            ));
        curl_setopt($myCurl,CURLOPT_POST, true);
        curl_setopt($myCurl,CURLOPT_POSTFIELDS, json_encode($data));
        $response = curl_exec($myCurl);
        curl_close($myCurl);

        return $response;
    }
}