<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $data['subject']; ?> </title>
</head>
<body>

    <b>Estimado:</b> <?php echo $data['nombrecompleto']><br>

    <p>
        se le informa a matricula no curso<?php echo $data['nombrecurso'];>
        <b>Nome de usuario <?php echo $data['nombreusuario']></b>
        <b>senha: <?php echo $data['contrasenia']></b>

    </p>

    <b>Gracias</b>
    <?php date(); >
</body>
</html>
