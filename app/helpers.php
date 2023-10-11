<?php

use App\Support\ValuesStore\Setting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

if (!function_exists('array_reset_index')) {
    /**
     * Reset numeric index of an array recursively.
     *
     * @param array $array
     * @return array|\Illuminate\Support\Collection
     *
     * @see https://stackoverflow.com/a/12399408/5736257
     */
    function array_reset_index($array): array
    {
        $array = $array instanceof Collection
            ? $array->toArray()
            : $array;

        foreach ($array as $key => $val) {
            if (is_array($val)) {
                $array[$key] = array_reset_index($val);
            }
        }

        if (isset($key) && is_numeric($key)) {
            return array_values($array);
        }

        return $array;
    }
}
if (!function_exists('setting')) {
    function setting($key = null, $default = null)
    {
        if ($key === null) {
            return app(Setting::class);
        }

        return app(Setting::class)->get($key, $default);
    }
}

if (!function_exists('formatDate')) {
    function formatDate($date, $format = null): string
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::createFromFormat('Y-m-d H:i:s', $date);
        }

        return $date->format($format ?? 'd/m/Y');
    }
}

if (!function_exists('formatTime')) {
    function formatTime($date, $format): string
    {
        if (!$date instanceof Carbon) {
            $date = Carbon::createFromFormat($format, $date);
        }

        return $date->format('d/m/Y H:i') ?? '';
    }
}

if (!function_exists('intended')) {
    function intended($request, string $defaultUrl)
    {
        if (!empty($request->redirect_url)) {
            return redirect($request->redirect_url);
        }

        return redirect()->to($defaultUrl);
    }
}

function formatNumber($value)
{
    return number_format($value);
}

if (!function_exists('currentUser')) {
    function currentUser()
    {
        return Auth::guard('web')->user();
    }
}

if (!function_exists('currentAdmin')) {
    function currentAdmin()
    {
        return Auth::guard('admins')->user();
    }
}

if (!function_exists('logActivity')) {
    function logActivity($subjectModel, $actionName, $customProperties = [])
    {
        $activity = activity();
        $activity->causedBy(auth()->user());
        if ($subjectModel) {
            $activity->performedOn($subjectModel);
        }
        if (!empty($customProperties)) {
            $activity->withProperties($customProperties);
        }
        $activity->log($actionName);
        return $activity;
    }
}
if (!function_exists('site_get_mail_template')) {
    function site_get_mail_template($slug)
    {
        $option = \DB::table('mail_settings')
            ->where([
                ['slug', $slug],
            ])
            ->first();

        if (!empty($option->value)) {
            return !is_array($option->value) ? \json_decode($option->value, true) : $option->value;
        }
        return [];
    }
}

if (!function_exists('pos_post_url')) {
    function pos_post_url()
    {
        $url = config('app.pos_uri') . config('app.pos_shop_id') . '/products?api_key=' . config('app.pos_api_key');
        return $url;
    }
}

if (!function_exists('pos_get_url')) {
    function pos_get_url()
    {
        $url = config('app.pos_uri') . config('app.pos_shop_id') . '/variations?api_key=' . config('app.pos_api_key');
        return $url;
    }
}

if (!function_exists('pos_put_url')) {
    function pos_put_url($pos_id)
    {
        $url = config('app.pos_uri') . config('app.pos_shop_id') . "/products/$pos_id?api_key=" . config('app.pos_api_key');
        return $url;
    }
}

if (!function_exists('pos_post_purchase_url')) {
    function pos_post_purchase_url()
    {
        $url = config('app.pos_uri') . config('app.pos_shop_id') . "/purchases?api_key=" . config('app.pos_api_key');
        return $url;
    }
}

if (!function_exists('pos_post_separate_url')) {
    function pos_post_separate_url()
    {
        $url = config('app.pos_uri') . config('app.pos_shop_id') . "/purchases/separate?api_key=" . config('app.pos_api_key');
        return $url;
    }
}

if (!function_exists('callApi')) {
    function callApi($url, $method, $params = [], $headers = [])
    {
        $client = new Client(['base_uri' => $url]);

        try {
            $response = $client->request($method, $url, [
                'body' => $params,
                'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json'
                    ] + $headers,
                'http_errors' => false,
            ]);

            return json_decode($response->getBody());
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            dd($response, $responseBodyAsString);
        }

    }
}

if (!function_exists('getDropboxClient')) {
    function getDropboxClient()
    {
        $refreshToken = config('dropbox.refreshToken');
        $clientId = config('dropbox.clientId');
        $clientSecret = config('dropbox.clientSecret');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.dropbox.com/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "refresh_token=$refreshToken&grant_type=refresh_token&client_id=$clientId&client_secret=$clientSecret",
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response);
        $client = null;
        if ($data->access_token) {
            $client = new Spatie\Dropbox\Client($data->access_token);
        }
        return $client;
    }
}
