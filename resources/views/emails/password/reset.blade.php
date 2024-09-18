@extends('emails.layout')

@section('content')

    <div style="padding: 30px 5%;">
        <h1 style="color: #515a67; display: inline; font-size: 38px; font-weight: normal; line-height: 1.2;">Recupera tu contraseña</h1>
        <p style="color: #515a67; font-size: 16px; margin: 25px 0 0 0;">Haz clic en el siguiente enlace para restablecer tu contraseña.</p>
        <a href="{{ url('reset-password', $token) }}" style="background-color: #ffd200; border-radius: 9999px; display: inline-block; font-size: 15px; font-weight: normal; margin-top: 30px; padding: 13px 50px; text-decoration: none;">Recuperar contraseña</a>
        <p style="color: #515a67; font-size: 16px; margin: 25px 0 0 0;">Si no solicitaste este cambio, por favor ignora este correo electrónico.</p>
        <p style="color: #515a67; font-size: 16px; margin: 20px 0 0 0;">Tu contraseña se modificará hasta que accedas al enlace y elijas una nueva.</p>
    </div>

@endsection
