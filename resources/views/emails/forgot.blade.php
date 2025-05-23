<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Bem-vindo</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 0; margin: 0;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4f4f4;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background-color: #ffffff; margin: 20px auto; border-radius: 8px; overflow: hidden; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
                    <tr style="background-color: #FA8D20;">
                        <td align="center" style="padding: 20px;">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="max-height: 60px;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 30px;">
                            <h1 style="color: #333333;">Olá, {{ $email }}!</h1>
                            <p style="font-size: 16px; color: #555;">Voce solicitou um lembrete de senha</p>

                            <p style="font-size: 14px; color: #888;">Acesse o link para alterar sua senha. <a
                                    href="http://127.0.0.1:8000/change-password?email={{ $email }}">lINK
                                    AQUI</a>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #000000; padding: 20px; text-align: center; color: #ffffff;">
                            <p style="margin: 0; font-size: 14px;">&copy; {{ date('Y') }} Dflix Control. Todos os
                                direitos reservados.</p>
                            <p style="margin: 8px 0;">
                                <a href="https://seusite.com"
                                    style="color: #ffffff; text-decoration: none; margin: 0 10px;">
                                    🌐 Site
                                </a>
                                <a href="https://facebook.com/suaempresa"
                                    style="color: #ffffff; text-decoration: none; margin: 0 10px;">
                                    🔵 Facebook
                                </a>
                                <a href="https://instagram.com/suaempresa"
                                    style="color: #ffffff; text-decoration: none; margin: 0 10px;">
                                    📸 Instagram
                                </a>
                                <a href="https://wa.me/5511999999999"
                                    style="color: #ffffff; text-decoration: none; margin: 0 10px;">
                                    💬 WhatsApp
                                </a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
