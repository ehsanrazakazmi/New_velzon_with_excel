<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to {{ $appName }}</title>
</head>

<body
    style="margin:0; padding:0; background-color:#f5f7fa; font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif; color:#1f2933;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
        style="background-color:#f5f7fa; padding:32px 12px;">
        <tr>
            <td align="center">

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:640px; background-color:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 1px 3px rgba(16,24,40,0.08);">

                    <!-- Header bar -->
                    <tr>
                        <td style="background-color:#14532d; padding:22px 32px;">
                            <span style="color:#ffffff; font-size:17px; font-weight:700;">{{ $appName }}</span>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding:32px;">

                            <h1 style="margin:0 0 12px; font-size:23px; font-weight:700; color:#101828;">
                                Welcome to the team, {{ $user->name }}
                            </h1>

                            <p style="margin:0 0 24px; font-size:15px; line-height:1.6; color:#667085;">
                                Your {{ $appName }} account is ready &mdash; you have been added as
                                <strong style="color:#344054;">{{ $roleLabel }}</strong>.
                            </p>

                            <!-- Login details -->
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="background-color:#eef2f6; border-radius:8px; margin-bottom:26px;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p
                                            style="margin:0 0 12px; font-size:11px; font-weight:700; letter-spacing:0.06em; text-transform:uppercase; color:#667085;">
                                            Your account
                                        </p>
                                        <p style="margin:0 0 8px; font-size:14px; color:#344054;">
                                            <strong style="color:#101828;">Email:</strong>
                                            <a href="mailto:{{ $user->email }}"
                                                style="color:#175cd3; text-decoration:none;">{{ $user->email }}</a>
                                        </p>
                                        <p style="margin:0; font-size:14px; color:#344054;">
                                            <strong style="color:#101828;">Password:</strong>
                                            you will choose your own when you sign in below.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA -->
                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin-bottom:24px;">
                                <tr>
                                    <td style="background-color:#14532d; border-radius:6px;">
                                        <a href="{{ $signInUrl }}"
                                            style="display:inline-block; padding:13px 26px; font-size:15px; font-weight:600; color:#ffffff; text-decoration:none;">
                                            Sign in to {{ $appName }}
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 18px; font-size:14px; line-height:1.6; color:#667085;">
                                This button is the only way to open your account for the first time &mdash;
                                signing in at <a href="{{ $loginUrl }}"
                                    style="color:#175cd3; text-decoration:none;">{{ $loginUrl }}</a> will not work until
                                you have used it. You will be asked to choose your own password straight away.
                            </p>

                            <p style="margin:0; font-size:13px; line-height:1.6; color:#98a2b3;">
                                This link can only be used once and expires {{ $expiresInHours }} hours after
                                this email was sent. If you were not expecting this email, please tell your
                                administrator &mdash; do not forward this message to anyone.
                            </p>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>

</html>
