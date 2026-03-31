<?php

use App\Data\SalesOrderData;
use App\Models\SalesOrder;
use App\Services\PaymentMethodQueryService;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public SalesOrder $sales_order;

    public function paymentService()
    {
        return app(PaymentMethodQueryService::class);
    }

    #[Computed()]
    public function order()
    {
        return SalesOrderData::fromModel($this->sales_order);
    }

    #[Computed()]
    public function paymentMethod()
    {
        return $this->order->payment->label;
    }

    #[Computed()]
    public function isRedirect()
    {
        return $this->paymentService()->shouldShowButton($this->order);
    }

    #[Computed()]
    public function redirectUrl()
    {
        return $this->paymentService()->getRedirectUrl($this->order);
    }
};
