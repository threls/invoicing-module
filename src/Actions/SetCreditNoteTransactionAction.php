<?php

namespace Threls\ThrelsInvoicingModule\Actions;

use Threls\ThrelsInvoicingModule\Dto\SetCreditNoteTransactionDto;

class SetCreditNoteTransactionAction
{
    public function execute(SetCreditNoteTransactionDto $setCreditNoteTransactionDto): void
    {
        $setCreditNoteTransactionDto->creditNote
            ->update(['transaction_id', $setCreditNoteTransactionDto->transaction->id]);

    }
}
