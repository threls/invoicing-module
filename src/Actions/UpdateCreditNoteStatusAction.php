<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Threls\ThrelsInvoicingModule\Dto\UpdateCreditNoteStatusDto;
use Threls\ThrelsInvoicingModule\Enums\CreditNoteStatusEnum;
use Threls\ThrelsInvoicingModule\Models\CreditNote;

class UpdateCreditNoteStatusAction
{
    public function execute(UpdateCreditNoteStatusDto $updateCreditNoteStatusDto)
    {
        /** @var CreditNote $creditNote */
        $creditNote = CreditNote::find($updateCreditNoteStatusDto->creditNoteId);

        /** @phpstan-ignore-next-line */
        if (! CreditNoteStatusEnum::from($creditNote->status)->canTransitionTo($updateCreditNoteStatusDto->status)) {
            throw new BadRequestHttpException('Credit note status cannot be transitioned to '.$updateCreditNoteStatusDto->status->value);
        }
        $creditNote->setStatus($updateCreditNoteStatusDto->status->value, $updateCreditNoteStatusDto->reason);

    }
}
