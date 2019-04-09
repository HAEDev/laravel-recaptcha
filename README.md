# Recaptcha middleware for Laravel

### Publish config file
composer vendor:publish --tag=config

### Usage
- Set `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET` in .env file
- Add `HAEDev\Recaptcha\RecaptchaServiceProvider::class` to the list of providers in config/app.php
- Put `@recaptcha` where you want to show the captcha in the blade file
- Assign the middleware `HAEDev\Recaptcha\Middleware\Recaptcha::class` to a route that submits the form.

### Google reCAPTCHA v2 credentials 
- Create reCAPTCHA v2, fill out form and submit
- Copy `SITE KEY` and `SECRET KEY`
- Set `RECAPTCHA_SITE_KEY`, `RECAPTCHA_SECRET` in .env file
