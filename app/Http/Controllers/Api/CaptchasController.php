<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;

//use App\Http\Requests\Api\CaptchaRequest;

class CaptchasController extends Controller
{
    public function store(Request $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-' . str_random(15);

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);
        \Cache::put($key, ['code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key'           => $key,
            'code'                  => $captcha->getPhrase(),
            'captcha_image_content' => $captcha->inline(),
            'expired_at'            => $expiredAt->toDateTimeString(),
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
