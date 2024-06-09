<x-mail::message>
# Order Sudah Masuk

Terima Kasih Telah Order Di Tempat Kami, Nomer Order Anda: {{$order->id}}.

<x-mail::button :url="$url">
    Lihat Order 
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
