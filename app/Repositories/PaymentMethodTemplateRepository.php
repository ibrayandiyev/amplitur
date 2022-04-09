<?php

namespace App\Repositories;

use App\Models\PaymentMethodTemplate;
use Illuminate\Database\Eloquent\Collection;

class PaymentMethodTemplateRepository extends Repository
{
    public function __construct(PaymentMethodTemplate $model)
    {
        $this->model = $model;
    }

    /**
     * [massUpdate description]
     *
     * @param   array  $paymentMethodTemplates  [$paymentMethodTemplates description]
     *
     * @return  [type]                          [return description]
     */
    public function massUpdate(array $paymentMethodTemplates)
    {
        foreach ($paymentMethodTemplates as $id => $attributes) {
            $paymentMethodTemplate = $this->find($id);
            $attributes['tax'] = sanitizeMoney($attributes['tax']);
            $attributes['discount'] = sanitizeMoney($attributes['discount']);
            $this->update($paymentMethodTemplate, $attributes);
        }
    }

    /**
     * [getInternationals description]
     *
     * @return  Collection[return description]
     */
    public function getNationals(): ?Collection
    {
        $templates = $this->model
            ->from('payment_method_templates')
            ->join('payment_methods', 'payment_method_templates.payment_method_id', '=', 'payment_methods.id')
            ->where('payment_methods.category', 'national')
            ->get('payment_method_templates.*');
    
        return $templates;
    }

    /**
     * [getInternationals description]
     *
     * @return  Collection[return description]
     */
    public function getInternationals(): ?Collection
    {
        $templates = $this->model
            ->from('payment_method_templates')
            ->join('payment_methods', 'payment_method_templates.payment_method_id', '=', 'payment_methods.id')
            ->where('payment_methods.category', 'international')
            ->get('payment_method_templates.*');
    
        return $templates;
    }
}