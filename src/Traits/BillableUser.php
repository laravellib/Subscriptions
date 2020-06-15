<?php

namespace TypiCMS\Modules\Subscriptions\Traits;

use Laravel\Cashier\Billable;

trait BillableUser
{
    use Billable;

    public function taxPercentage()
    {
        return $this->tax_percentage;
    }

    /**
     * Get the receiver information for the invoice.
     * Typically includes the name and some sort of (E-mail/physical) address.
     */
    public function getInvoiceInformation(): array
    {
        return [$this->first_name.' '.$this->last_name, $this->email, $this->street.' '.$this->number, $this->zip.' '.$this->city, $this->country];
    }

    /**
     * Get additional information to be displayed on the invoice. Typically a note provided by the customer.
     */
    public function getExtraBillingInformation(): ?string
    {
        return null;
    }

    public function mollieCustomerFields(): array
    {
        return [
            'email' => $this->email,
            'name' => "$this->first_name $this->last_name",
            'locale' => $this->getLocale(),
            'metadata' => [
                'id' => $this->id,
                'street' => $this->street,
                'number' => $this->number,
                'zip' => $this->zip,
                'city' => $this->city,
                'country' => $this->country,
            ],
        ];
    }

    /**
     * @return string
     *
     * @see https://docs.mollie.com/reference/v2/payments-api/create-payment#parameters
     *
     * @example 'nl_NL'
     */
    public function getLocale()
    {
        switch (app()->getLocale()) {
            case 'fr':
                $locale = 'fr_FR';

                break;

            case 'nl':
                $locale = 'nl_NL';

                break;

            case 'de':
                $locale = 'de_DE';

                break;

            case 'it':
                $locale = 'it_IT';

                break;

            case 'es':
                $locale = 'es_ES';

                break;

            case 'pt':
                $locale = 'pt_PT';

                break;

            default:
                $locale = 'en_US';

                break;
        }

        return $locale;
    }

    public function hasRunningSubscription(): bool
    {
        $hasActiveSubscription = false;

        foreach ($this->subscriptions as $subscription) {
            if ($subscription->active()) {
                $hasActiveSubscription = true;
            }
        }

        return $hasActiveSubscription;
    }
}
