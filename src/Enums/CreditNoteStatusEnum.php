<?php

namespace Threls\ThrelsInvoicingModule\Enums;

enum CreditNoteStatusEnum: string
{
    case PENDING = 'pending';
    case SUCCEEDED = 'succeeded';
    case FAILED = 'failed';
    case REQUIRES_ACTION = 'requires_action';
    case CANCELED = 'canceled';

    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PENDING => [self::SUCCEEDED, self::FAILED, self::REQUIRES_ACTION, self::CANCELED],
            self::REQUIRES_ACTION => [self::SUCCEEDED, self::FAILED, self::CANCELED, self::PENDING],
            default => [],
        };
    }

    public function canTransitionTo(self $newState): bool
    {
        return in_array($newState, $this->allowedTransitions(), true);
    }
}
