<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <p><span style="font-size:14px">This email confirms that your password has changed.</p>

        <p>To reset your password {!! link_to('password/reset/' . $token, 'click on this link') !!}.<br>{{ 'This link will expire in ' . config('auth.reminder.expire', 60) . ' minutes' }}.</p>

        <p>Thank you,</p>

        <p>SATS COMPANION TEAM</p>

        <p>If you would like to contact SATs Companion, please <a href="{{ $enquery_url }}">send your query</a> and one of our team will get back to you shortly. </p>

        <p> </p>

        <p>The information in this email and any attachments is confidential and is intended solely for the addressee. If an addressing or transmission error has misdirected this email, please notify the author. {{ $site_url }}</p>
    </body>
</html>




