<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
</head>
<body>
    <p>Dear user,</p>
    <p>You are receiving this email because we received a password reset request for your account.</p>
    <p>Reset Link:
        <a href="{{ $url }}/#/forgot-password/enter-new-password/{{ $code }}">{{ $url }}/#/forgot-password/enter-new-password/{{ $code }}</a>
    </p>
    <p>If you did not request a password reset, no further action is required.</p>
    <p>Regards,</p>
    <p>{{ $appName }}
</body>
</html>
