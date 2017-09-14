@component('mail::message')
# Your infomation

User name: {{ $user->username }}<br>
Password: {{ $user->password }}<br>
Name: {{ $user->name }}<br>

@component('mail::button', ['url' => 'http://127.0.0.1:8000/login'])
Link to website
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
