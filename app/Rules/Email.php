<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class Email implements ValidationRule
{
    protected array $blockedDomains = [
        'mail.ru', 'bk.ru', 'inbox.ru',
        'list.ru', 'yandex.ru', 'yandex.com',
        'ya.ru', 'rambler.ru', 'sendmail.ru',
        'imail.ru', 'mail.su',
    ];

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = strtolower(Str::after($value, '@'));

        if (in_array($domain, $this->blockedDomains, true)) {
            $fail('validation.email_blocked')->translate();
        }
    }
}
