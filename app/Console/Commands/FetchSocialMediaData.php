<?php

namespace App\Console\Commands;

use Abraham\TwitterOAuth\TwitterOAuth;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class FetchSocialMediaData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-social-media';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch liked tweets and Instagram user media, combine them, and store as JSON';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        //
        $twitterUid = env('TWITTER_USER_ID');
        $igAct = env('INSTAGRAM_ACCESS_TOKEN');

        if (!$twitterUid || !$igAct) {
            $this->error('Please set TWITTER_USER_ID and INSTAGRAM_ACCESS_TOKEN in your .env file.');
            return 1;
        }

        $this->info('Fetching up to 50 liked tweets...');
        $twitterData = $this->fetchLikedTweets($twitterUid);

//        $this->info('Fetching Instagram media...');
//        $instagramData = $this->fetchInstagramMedia($igAct);

        $this->info('Combining data...');
//        $combinedData = $this->combineData($twitterData, $instagramData);
        $combinedData = $twitterData;
        // Save to JSON file
        $this->info('Saving data to JSON...');
        $filePath = storage_path('app/social_media_data.json');
        file_put_contents($filePath, json_encode($combinedData, JSON_PRETTY_PRINT));

        $this->info("Data saved to $filePath");
        return 0;
    }

    private function buildAuthorizationHeader($oauthParams)
    {
        // Start the header string
        $header = 'OAuth ';

        // Iterate through OAuth parameters to build the header string
        $values = [];
        foreach ($oauthParams as $key => $value) {
            $encodedKey = rawurlencode($key); // Percent encode key
            $encodedValue = rawurlencode($value); // Percent encode value
            $values[] = "$encodedKey=\"$encodedValue\""; // Format key="value"
        }

        // Join all parameters with a comma and a space
        $header .= implode(', ', $values);

        return $header;
    }

    function generateOauthSignature($url, $method, $params, $consumerKey, $consumerSecret, $token, $tokenSecret)
    {
        ksort($params);

        $parameterString = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
        $signatureBaseString = strtoupper($method) . '&' . rawurlencode($url) . '&' . rawurlencode($parameterString);

        $signingKey = rawurlencode($consumerSecret) . '&' . rawurlencode($tokenSecret);
        $signature = base64_encode(hash_hmac('sha1', $signatureBaseString, $signingKey, true));

        dump($signature);
        return $signature;
    }



    private function fetchLikedTweets($twitterUserId): array
    {
        $url = 'https://api.twitter.com/2/users/' . $twitterUserId . '/liked_tweets';

        $method = 'GET';
        $consumerKey = env('CONSUMER_KEY');
        $consumerSecret = env('CONSUMER_SECRET');
        $token = env('TWITTER_ACCESS_TOKEN');
        $tokenSecret = env('TWITTER_TOKEN_SECRET');

        // Parameters for the request
        $params = [
            'tweet.fields' => 'text,created_at,attachments',
            'expansions' => 'attachments.media_keys',
            'media.fields' => 'url',
            'max_results' => '90',
            'oauth_consumer_key' => $consumerKey,
            'oauth_token' => $token,
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_nonce' => uniqid(),
            'oauth_version' => '1.0',
        ];

        // Generate the OAuth signature
        $params['oauth_signature'] = $this->generateOauthSignature($url, $method, $params, $consumerKey, $consumerSecret, $token, $tokenSecret);

        // Build the Authorization header
        $oauthparams = [
            'oauth_consumer_key' => $params['oauth_consumer_key'],
            'oauth_nonce' => $params['oauth_nonce'],
            'oauth_signature' => $params['oauth_signature'],
            'oauth_signature_method' => $params['oauth_signature_method'],
            'oauth_timestamp' => $params['oauth_timestamp'],
            'oauth_token' => $params['oauth_token'],
            'oauth_version' => $params['oauth_version'],
        ];
        $authHeader = $this->buildAuthorizationHeader($oauthparams);
        // Remove non-URL query parameters
        unset($params['oauth_signature']);
        unset($params['oauth_consumer_key']);
        unset($params['oauth_token']);
        unset($params['oauth_signature_method']);
        unset($params['oauth_timestamp']);
        unset($params['oauth_nonce']);
        unset($params['oauth_version']);

        // Perform the HTTP GET request
        $response = Http::withHeaders([
            'Authorization' => $authHeader,
            'Content-Type' => 'application/json',
        ])->get($url, $params)->json();


        $filteredData = [];

        if (isset($response['data']) && isset($response['includes']['media'])) {
            // Create a map of media items keyed by their media_key
            $mediaMap = collect($response['includes']['media'])->keyBy('media_key');

            foreach ($response['data'] as $tweet) {
                if (isset($tweet['attachments']['media_keys'])) {
                    foreach ($tweet['attachments']['media_keys'] as $mediaKey) {
                        if ($mediaMap->has($mediaKey)) {
                            $media = $mediaMap[$mediaKey];
                            if ($media['type'] === 'photo') {
                                $filteredData[] = [
                                    'text' => $tweet['text'],
                                    'image_url' => $media['url'],
                                    'source' => 'twitter',
                                ];
                            }
                        }
                    }
                }
            }
        } else {
            $this->warn('No liked tweets with images found or an error occurred.');
        }


        return $filteredData;
    }

    private function fetchInstagramMedia($accessToken): array
    {
        $url = "https://graph.instagram.com/me/media";
        $params = [
            'fields' => 'id,caption,media_type,media_url,timestamp',
            'access_token' => $accessToken,
        ];

        $filteredData = [];

        $processResponse = function ($response) use (&$filteredData) {
            if (isset($response['data'])) {
                foreach ($response['data'] as $media) {
                    if (in_array($media['media_type'], ['IMAGE', 'CAROUSEL_ALBUM'])) {
                        $filteredData[] = [
                            'text' => $media['caption'] ?? '',
                            'media_id' => $media['id'],
                            'source' => 'instagram',
                        ];

                    }
                }
            }
        };

        $response = Http::get($url, $params)->json();
        while (isset($response['paging']['next'])){
            $response = Http::get($response['paging']['next'])->json();
            $processResponse($response);
        }
        do {
            dump($response);
            if (isset($response['data'])) {
                foreach ($response['data'] as $media) {
                    if (in_array($media['media_type'], ['IMAGE', 'CAROUSEL_ALBUM'])) {
                        $filteredData = array_merge($filteredData, [
                                                    'text' => $media['caption'] ?? '',
                                                    'image_url' => $media['media_url'],
                                                    'source' => 'instagram',
                                                ]);

                    }
                }
            } else {
                $this->warn('No Instagram media with images found or an error occurred.');
                break;
            }

            $url = $response['paging']['next'] ?? null;
            $response = Http::get($response['paging']['next'])->json();
        } while ($url);

        return $filteredData;
    }
    private function combineData($twitterData, $instagramData): array
    {
        $this->info("combine");
        $combined = array_merge($twitterData, $instagramData);
        shuffle($combined);
//        $this->info(dump($combined));
        return $combined;
    }
}
