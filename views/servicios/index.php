<h1 class="nombre-pagina">Servicios</h1>
<p class="descripcion-pagina">Administracion de servicios</p>


<?php
    include_once __DIR__ . '/../templates/barra.php';
    
?>
<ul class="servicios">
    <?php foreach($servicios as $servicio) { ?> 
        <li>
            <p><span>Nombre: </span><?php echo $servicio->nombre; ?></p>
            <p><span>Precio: </span><?php echo $servicio->precio; ?></p>
        </li>
        <div class="acciones">
            <a class="boton" href="/servicios/actualizar?id=<?php echo $servicio->id; ?>">Actualizar</a>
            
            <form action="/servicios/eliminar" method="POST">
                <input type="hidden" name="id" value="<?php echo $servicio->id; ?>">
                <input type="submit" class="boton-eliminar" value="Eliminar">
            </form>
        </div>

    <?php } ?>
</ul>