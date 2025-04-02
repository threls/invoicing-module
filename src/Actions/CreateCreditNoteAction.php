<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\CreateCreditNoteDto;
use Threls\ThrelsInvoicingModule\Models\CreditNote;
use Threls\ThrelsInvoicingModule\Models\Transaction;

class CreateCreditNoteAction
{
    public function execute(CreateCreditNoteDto $createCreditNoteDto)
    {
        /** @var Transaction $transaction */
        $transaction = Transaction::find($createCreditNoteDto->transactionId);

        /** @var CreditNote $creditNote */
        $creditNote = $transaction->creditNote()->create([
            'amount' => $createCreditNoteDto->amount,
            'currency' => $createCreditNoteDto->currency,
            'reason' => $createCreditNoteDto->reason,
        ]);

        $creditNote->setStatus($createCreditNoteDto->status->value, $createCreditNoteDto->statusReason);

        return $creditNote;
    }
}
