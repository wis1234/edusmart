<x-mail::message>
# Password Reset Request

Hello {{ $notifiable->first_name }},

You are receiving this email because we received a password reset request for your **EduSmart** account.

<x-mail::button :url="$url">
Reset Password
</x-mail::button>

This password reset link will expire in **5 minutes**.

If you did not request a password reset, please ignore this email. Your account will remain secure.

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message> 