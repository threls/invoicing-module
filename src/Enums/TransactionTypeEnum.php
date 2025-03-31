<?php

namespace Threls\ThrelsInvoicingModule\Enums;

enum TransactionTypeEnum: string
{
    case PURCHASE = 'purchase';
    case CREDIT_NOTE = 'credit_note';

}
