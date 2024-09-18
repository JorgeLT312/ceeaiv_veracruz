@extends('emails.layout')

@section('content')

    <div style="padding: 30px 5%;">
        <h1 style="color: #515a67; display: inline; font-size: 38px; font-weight: normal; line-height: 1.2;">Actualización de contraseña</h1>
        <p style="color: #515a67; font-size: 16px; margin: 25px 0 0 0;">Se ha actualizado contraseña desde la plataforma.</p>
        <a href="{{ config('app.url') }}" style="background-color: #ffd200; border-radius: 9999px; display: inline-block; font-size: 15px; font-weight: normal; margin-top: 30px; padding: 13px 50px; text-decoration: none;">Ingresar al sitio</a>
        <p style="color: #515a67; font-size: 16px; margin: 25px 0 0 0;">La contraseña es: <strong> {{ $data }} </strong></p>
    </div>

@endsection
