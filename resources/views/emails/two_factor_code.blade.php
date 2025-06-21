@component('mail::message')
<div style="text-align:center; margin-bottom: 1.5rem;">
    <span style="font-size:2rem; font-weight:800; color:#6366f1; letter-spacing:0.05em;">Edu<span style="color:#111827;">Smart</span></span>
</div>

# Hello{{ $fullName ? ' ' . $fullName : '' }}!

Your two-factor authentication code is:

@component('mail::panel')
<span style="font-size:2.5rem; font-weight:700; color:#6366f1; letter-spacing:0.2em; display:block; text-align:center;">{{ $code }}</span>
@endcomponent

This code will expire in <span style="color:#a21caf; font-weight:600;">2 minutes</span>.

If you did not request this code, please ignore this email.

@slot('subcopy')
<div style="text-align:center; color:#64748b; font-size:0.9rem; margin-top:2rem;">
    Best regards,<br>
    <span style="color:#6366f1; font-weight:600;">EduSmart Team</span>
</div>
@endslot
@endcomponent 