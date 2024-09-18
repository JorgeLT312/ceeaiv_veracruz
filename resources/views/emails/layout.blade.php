<!doctype html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <title>CEEAIV Veracruz</title>
        <link href="{{ asset('fonts/Panton/stylesheet.css') }}" rel="stylesheet">
    </head>
    <body style="font-family: 'Panton', sans-serif; font-weight:normal; background-color: #ffffff; margin: 0; padding: 20px 15px;">
        <center>
            <table width="100%" cellpadding="0" cellspacing="0" style="--tw-bg-opacity: 1; background-color: #f2f2f2; border-radius: 16px; height: auto; width: 100%; border-collapse: collapse; max-width: 600px; width: 100%;">
                <tr>
                    <td style="padding:20px;">
                        <img src="{{ asset('images/veracruz_color.png') }}" style="height: 4.5rem;" alt="veracruz">
                        <img src="{{ asset('images/ceeaiv.png') }}" style="height: 4.5rem;" alt="usaid">
                    </td>
                </tr>
                <tr>
                    <td>
                        @yield('content')
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #841530; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; text-align: center; padding:20px;">
                        <img src="{{ asset('images/veracruz.png') }}" style="height: 3rem;" alt="veracruz">
                        <img src="{{ asset('images/usaid.png') }}" style="height: 3rem;" alt="usaid">
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html>
