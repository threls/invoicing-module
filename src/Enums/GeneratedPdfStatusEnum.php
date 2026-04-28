<?php

namespace Threls\ThrelsInvoicingModule\Enums;

enum GeneratedPdfStatusEnum: string
{
    case PENDING = 'pending';
    case IN_PROGRESS = 'in_progress';
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';
}
