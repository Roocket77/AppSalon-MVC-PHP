<h1 class="nombre-pagina">OLVIDE PASSWORD</h1>
<p class="descripcion-pagina">Restablese tu password, ecribiendo tu email a continuacion</p>

<?php 
    include_once __DIR__ . "/../templates/alertas.php";
?>

<form action="/olvide" class="formulario" method="POST">
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            placeholder="Tu Email"
            name="email"
        />
    </div>

    <input type="submit" class="boton" value="Enviar instrucciones">
</form>


<div class="acciones">
    <a href='/login'>Ya tienes una cuenta? Inicia sesion</a>
    <a href='/crear-cuenta'>Aun no tienes una cuenta? Crear una</a>
    
</div>