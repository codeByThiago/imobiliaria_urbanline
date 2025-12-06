<?php 

namespace Services;

use Google\Client as GoogleClient;
use Google\Service\Oauth2;

class GoogleAuthService {
    private GoogleClient $client;

    public function __construct() {
        $this->client = new GoogleClient();
        $this->client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
        $this->client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
        $this->client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);
        $this->client->addScope('openid');
        $this->client->addScope('email');
        $this->client->addScope('profile');
    }

    public function getAuthUrl(): string {
        return $this->client->createAuthUrl();
    }

    public function getUserFromCode(string $authCode): ?array {
        try {
            $token = $this->client->fetchAccessTokenWithAuthCode($authCode);

            if (isset($token['error'])) {
                return null;
            }

            $this->client->setAccessToken($token);
            $oauth2 = new Oauth2($this->client);
            $googleUser = $oauth2->userinfo->get();

            // 🔥 Converte o objeto em array associativo
            return [
                'id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'picture' => $googleUser->picture,
            ];
        } catch (\Exception $e) {
            throw new \Exception("Erro no GoogleService: " . $e->getMessage());
        }
    }
    }

?>