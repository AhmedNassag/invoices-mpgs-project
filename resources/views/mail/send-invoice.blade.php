<x-mail::message>
# Your invoice ID - {{ green_invoice_no($invoice?->id) }}

<x-mail::panel>
{{ $message }}
</x-mail::panel>

<x-mail::button :url="$url">
View Invoice
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>