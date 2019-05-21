@component('mail::message')

Здравствуйте, {{ $customer_name }}!

Вы записались на сеанс тату:

Название: {{ $tatoo_name }}
Стоимость: {{ $price }} ₽.

Дата и время: {{ $order_note_date }}

@component('mail::button', ['url' => $url, 'color' => 'primary'])
    Подробнее
@endcomponent

Спасибо, что пользуетесь услугами <br>
{{ config('app.name') }}
@endcomponent
