<x-mail::message>
# Two-Factor Authentication Enabled

Hello {{ $notifiable->first_name ?? $notifiable->name ?? 'there' }},

Your **EduSmart** account now has two-factor authentication enabled. This adds an extra layer of security to protect your account.

## What this means:
- You'll receive a verification code via email each time you log in
- Your account is now more secure against unauthorized access
- You can disable this feature anytime from your account settings

If you did not enable this feature, please contact our support team immediately.

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message> 