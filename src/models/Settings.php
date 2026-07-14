<?php

namespace recranet\redirects\models;

use craft\base\Model;

class Settings extends Model
{
    /**
     * Whether to automatically create a redirect when an element's URI changes.
     */
    public bool $autoCreateRedirects = true;

    /**
     * HTTP status code used for automatically created redirects.
     */
    public int $autoRedirectType = 301;

    protected function defineRules(): array
    {
        return [
            ['autoCreateRedirects', 'boolean'],
            ['autoRedirectType', 'in', 'range' => [301, 302, 307, 308]],
        ];
    }
}
