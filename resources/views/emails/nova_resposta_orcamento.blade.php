<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resposta à solicitação de orçamento!</title>
</head>
<body style="margin:0;padding:0;background-color:#f4f4f5;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f5;padding:40px 0;">
    <tr>
        <td align="center">
            <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff;border-radius:8px;overflow:hidden;max-width:600px;width:100%;">
                <tr>
                    <td style="background-color:#2563eb;padding:32px 40px;text-align:center;">
                        <h1 style="margin:0;color:#ffffff;font-size:24px;font-weight:700;">Workinhoo</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding:40px;">
                        <p style="margin:0 0 16px;color:#111827;font-size:16px;">Olá, <strong>{{ $nome }}</strong>!</p>
                        <p style="margin:0 0 24px;color:#374151;font-size:15px;line-height:1.6;">
                            O prestador {{ $prestador }} respondeu à sua solicitação de orçamento! Acesse o seu perfil para visualizar.
                        </p>
                        <p style="margin:0;color:#374151;font-size:15px;">
                            Atenciosamente,<br>
                            <strong>Equipe Workinhoo</strong>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="background-color:#f9fafb;padding:24px 40px;border-top:1px solid #e5e7eb;">
                        <p style="margin:0;color:#9ca3af;font-size:12px;text-align:center;line-height:1.6;">
                            Este é um e-mail automático. Não responda a esta mensagem.<br>
                            &copy; {{ date('Y') }} Workinhoo. Todos os direitos reservados.
                        </p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
