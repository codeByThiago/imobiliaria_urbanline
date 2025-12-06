<?php 

// namespace Services;

// use Google\Service\Oauth2;
// use Google\Client;

// class GoogleAuthService {
//     private $client;

//     public function __construct() {
//         $this->client = new Client();
//         $this->client->setClientId($_ENV['CLIENT_ID']);
//         $this->client->setClientSecret($_ENV['CLIENT_SECRET']);
//         $this->client->setRedirectUri($_ENV['REDIRECT_URI']);
//         $this->client->addScope(['email', 'profile']);
//     }

//     public function getAuthUrl() {
//         return $this->client->createAuthUrl();
//     }

//     public function getUserInfo($code) : array {
//         $token = $this->client->fetchAccessTokenWithAuthCode($code);
//         $this->client->setAccessToken($token['access_token']);

//         $google_service = new Oauth2($this->client);
//         $google_info = $google_service->userinfo->get();
        
//         return [
//             'nome' => $google_info->name,
//             'email' => $google_info->email,
//             'foto' => $google_info->picture
//         ];
//     }


// }

?>