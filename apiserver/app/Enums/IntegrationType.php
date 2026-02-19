<?php

namespace App\Enums;

enum IntegrationType: string
{
    case Payment = 'payment';
    case Payout = 'payout';
    case Sms = 'sms';
    case Shipping = 'shipping';
}

