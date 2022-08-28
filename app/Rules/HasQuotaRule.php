<?php

namespace App\Rules;

use App\Services\StorageService;
use Illuminate\Contracts\Validation\Rule;

class HasQuotaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return StorageService::hasQuota($value->getSize());
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Недостаточно места на диске!';
    }
}
