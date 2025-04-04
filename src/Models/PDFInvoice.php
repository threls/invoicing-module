<?php

namespace Threls\ThrelsInvoicingModule\Models;

use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\View;

class PDFInvoice extends \LaravelDaily\Invoices\Invoice
{
    public function render()
    {
        if ($this->pdf) {
            return $this;
        }

        $this->beforeRender();

        $template = sprintf('invoicing-module::templates.%s', $this->template);
        $view     = View::make($template, ['invoice' => $this]);
        $html     = mb_convert_encoding($view, 'HTML-ENTITIES', 'UTF-8');

        $this->pdf = PDF::setOptions($this->options)
            ->setPaper($this->paperOptions['size'], $this->paperOptions['orientation'])
            ->loadHtml($html);
        $this->output = $this->pdf->output();

        return $this;
    }

    public function toHtml()
    {
        $template = sprintf('invoicing-module::templates.%s', $this->template);

        return View::make($template, ['invoice' => $this]);
    }

}
