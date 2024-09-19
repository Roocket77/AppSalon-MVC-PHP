
<div class="barra">
    <p>Hola <strong><?php echo $_SESSION['nombre']  ?? ''; ?> !!</strong> </p>

    <a class="boton-session" href="/logout">Cerrar Sesión</a>
</div>

<?php if(isset($_SESSION['admin'])){
   
?>
<div class="barra-servicios">
    <a class="boton" href="/admin">Ver citas</a>
    <a class="boton" href="/servicios">Ver Servicios</a>
    <a class="boton" href="/servicios/crear">Nuevos Servicios</a>
    

</div>


<?php } ?>