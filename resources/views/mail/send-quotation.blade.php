<x-mail::message>
# Your quotation ID - {{ green_quotation_no($invoice?->id) }}

<x-mail::panel>
{{ $message }}
</x-mail::panel>

<x-mail::button :url="$url">
View Quotation
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>