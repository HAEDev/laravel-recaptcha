<?php namespace HAEDev\Recaptcha\Middleware;

use Illuminate\Http\Request;
use Closure;

class Recaptcha {
    public function handle(Request $request, Closure $next, $guard = null) {
        // disabled
        if (!config('recaptcha.enabled')) {
            return $next($request);
        }
        
        $ch = null;
        try {
            $captcha = $request->input('g-recaptcha-response', '');
            if ($captcha === '') {
                return $this->fail('Invalid input');
            }
            
            $requestData = [
                'secret' => config('recaptcha.secret'),
                'response' => $captcha,
                'remoteip' => $request->ip(),
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_VERBOSE, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);
            if (empty($response)) {
                $error = curl_error($ch);
                return $this->fail($error? $error : 'Connection error');
            }

            $responseData = json_decode($response, true);
            $success = $responseData
                && isset($responseData['success'])
                && $responseData['success'];
            
            if ($success) {
                return $next($request);
            }
        } catch (\Throwable $error) {
            return $this->fail($error->getMessage());
        } finally {
            if ($ch) {
                curl_close($ch);
            }
        }
        return $this->fail('Wrong captcha');
    }
    
    private function fail($error) {
        return redirect()->back()->withErrors(['recaptcha' => $error]);
    }
}
