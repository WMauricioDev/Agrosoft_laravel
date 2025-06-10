<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 1. Solo letras (incluye tildes) y espacios
        Validator::extend('alpha_space', function($attribute, $value) {
            return preg_match('/^[\pL\s]+$/u', $value);
        });
        Validator::replacer('alpha_space', function($message, $attribute) {
            return ":attribute solo puede contener letras y espacios.";
        });

        // 2. Alfanumérico + guiones bajos y medios (p.ej. usernames o slugs)
        Validator::extend('alpha_dash', function($attribute, $value) {
            return preg_match('/^[A-Za-z0-9_-]+$/', $value);
        });
        Validator::replacer('alpha_dash', function($message, $attribute) {
            return ":attribute solo puede contener letras, números, guiones bajos y medios.";
        });

        // 3. Números enteros o decimales con hasta dos dígitos
        Validator::extend('decimal_2', function($attribute, $value) {
            return preg_match('/^\d+(\.\d{1,2})?$/', $value);
        });
        Validator::replacer('decimal_2', function($message, $attribute) {
            return ":attribute debe ser un número entero o decimal con hasta 2 decimales.";
        });

        // 4. Fecha en formato YYYY-MM-DD
        Validator::extend('date_iso', function($attribute, $value) {
            return preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);
        });
        Validator::replacer('date_iso', function($message, $attribute) {
            return ":attribute debe tener el formato YYYY-MM-DD.";
        });

        // 5. Solo dígitos (números enteros positivos)
        Validator::extend('numeric_only', function($attribute, $value) {
            return preg_match('/^[0-9]+$/', $value);
        });
        Validator::replacer('numeric_only', function($message, $attribute) {
            return ":attribute solo puede contener dígitos.";
        });
    }

}
