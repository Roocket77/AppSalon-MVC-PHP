<h1 class="nombre-pagina">Panel de Administracion</h1>
<?php 
    include_once __DIR__ . '/../templates/barra.php'
?>
<h2>Buscar citas</h2>

<div class="busqueda">
    <form class="formulario">
        <div class="campo">
            <label for="fecha">Fecha</label>
            <input 
                type="date" 
                name="fecha" 
                id="fecha"
                value="<?php echo $fecha; ?>">

        </div>

    </form>
</div>

<?php
    if(count($citas) === 0){
        echo "<h2>No hay citas en esta fecha</h2>" ;
    }
?>

<div id="citas-admin">
    <ul class="citas">
        <?php 
        $idCita = 0;
        foreach($citas as $key => $cita){
            if($idCita !== $cita->id){
                $total = 0;
        ?>
            <li>
                <p> <span>ID: </span> <?php echo $cita->id; ?></p>
                <p> <span>Hora: </span> <?php echo $cita->hora; ?></p>
                <p> <span>Cliente: </span> <?php echo $cita->cliente; ?></p>
                <p> <span>Email: </span> <?php echo $cita->email; ?></p>
                <p> <span>Telefono: </span> <?php echo $cita->telefono; ?></p>
                <h3>Servicios</h3>
                
            <?php 
                $idCita = $cita->id;
            } //fin de if 
                $total += $cita->precio;

            ?>               
            </li>
                <p class="servicio"><?php echo $cita->servicio . " $" . $cita->precio; ?></p>
            
            <?php 
                $actual = $cita->id;
                $proximo = $citas[$key +1]->id ?? 0;
                if (esUltimo($actual, $proximo)){

            ?>
                    <p class="total">Total: <span>$ <?php echo $total; ?></span></p>

                    <form action="/api/eliminar" method="POST">
                        <input type="hidden" name="id" value="<?php echo $cita->id; ?>">
                        <input type="submit" class="boton-eliminar" value="Eliminar">
                    </form>

            <?php
                }
            ?>

        <?php } //fin de foreach ?>
        
    </ul>
    
</div>
<?php
    $script = "<script src='build/js/buscador.js'></script>";

?>

