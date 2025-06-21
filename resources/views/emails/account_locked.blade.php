<x-mail::message>
# Security Alert: Your Account Has Been Locked

Hello {{ $notifiable->first_name }},

We've detected multiple failed login attempts on your **EduSmart** account. For your security, the account has been temporarily locked.

If this was you, you can unlock your account by resetting your password.

<x-mail::button :url="$resetUrl">
Reset Your Password
</x-mail::button>

If you did not attempt to log in, your account is secure. Please do not click any links and you can disregard this email. The lock will prevent any further unauthorized access attempts.

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message> 