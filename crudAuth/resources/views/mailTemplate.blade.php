@component('mail::message')
# {{ $data['title'] }}

Dear {{ $data['name'] }}, 
You have been registered as new admin. This is the default password for your account, please change your password immediately by logging in and go into Profile page.  

Password : # {{ $data['password'] }}

@component('mail::button', ['url' => $data['url']])
Visit Page
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
