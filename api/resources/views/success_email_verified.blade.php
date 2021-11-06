<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                margin: 0;
                padding: 0;
                border: 0;
                box-sizing: border-box;
                font-family: 'Nunito', sans-serif;
                width: 100vw; 
                height: 100vh; 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                flex-direction: column;
                
            }

            .btn {
                width: 350px;
                padding: 20px;
                background-color: black;
                color: white;
                font-size: 18px;
                outline: none;
                border-radius: 10px;
                border: none;
                cursor: pointer;
                text-decoration: none;
                text-align: center;
            }

            .subtitle {
                margin-top: 0;
            }
        </style>
    </head>
    <body>
        <img src="{{URL::asset('/images/success_email_verified.svg')}}" width="200px" height="200px"/>
        <h1>E-mail validado com sucesso!</h1>
        <h2 class="subtitle">Muito obrigado por confirmar seu e-mail</h2>
        <a class="btn" href="/">Clique aqui para acessar o sistema</a>
    </body>
</html>
