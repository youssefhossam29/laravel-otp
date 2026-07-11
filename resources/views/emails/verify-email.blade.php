<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email - {{ config('app.name') }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; -webkit-font-smoothing: antialiased;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; padding: 40px 20px;">
        <tr>
            <td align="center">

                <!-- Main Card -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; background-color: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">

                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 32px 30px 24px; border-bottom: 1px solid #f1f5f9; background: linear-gradient(135deg, #4F46E5, #06B6D4); color: white; text-align: center;">
                            <h2 style="margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px;">
                                {{ config('app.name') }}
                            </h2>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h3 style="margin: 0 0 16px; font-size: 20px; font-weight: 600; color: #0f172a;">
                                Welcome, {{ $user->name }}!
                            </h3>

                            <p style="margin: 0 0 24px; font-size: 16px; color: #475569; line-height: 1.6;">
                                Thank you for signing up. To get started and ensure the security of your account, please verify your email address by clicking the button below.
                            </p>

                            <!-- CTA Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 32px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $url }}" style="display: inline-block; padding: 14px 32px; background: linear-gradient(135deg, #4F46E5, #06B6D4); color: #ffffff; font-size: 16px; font-weight: 600; text-decoration: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);">
                                            Verify Email Address
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 0; font-size: 14px; color: #64748b; line-height: 1.6;">
                                If the button doesn't work, you can copy and paste this link into your browser:<br>
                                <a href="{{ $url }}" style="color: #4F46E5; text-decoration: underline; word-break: break-all;">
                                    {{ $url }}
                                </a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 24px 30px; background-color: #f8fafc; border-top: 1px solid #e2e8f0;">
                            <p style="margin: 0 0 8px; font-size: 13px; color: #64748b; line-height: 1.5;">
                                If you did not create an account with us, no further action is required and you can safely ignore this email.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Main Card -->

            </td>
        </tr>
    </table>

</body>
</html>
