<x-mail::message>
# Two-Factor Authentication Disabled

Hello {{ $notifiable->first_name ?? $notifiable->name ?? 'there' }},

Two-factor authentication has been disabled for your **EduSmart** account.

## Security Reminder:
- Your account is now using only password protection
- Consider using a strong, unique password
- You can re-enable two-factor authentication anytime from your account settings

If you did not disable this feature, please:
1. Change your password immediately
2. Contact our support team
3. Re-enable two-factor authentication

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message> 