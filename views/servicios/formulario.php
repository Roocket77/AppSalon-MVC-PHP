<div class="campo">
    <label for="nombre">Nombre</label>
    <input 
        type="text" 
        name="nombre" 
        id="nombre"
        placeholder="Nombre servicio"
        value="<?php echo $servicio->nombre ?>"
        />
</div>

<div class="campo">
    <label for="precio">Precio</label>
    <input 
        type="number" 
        name="precio" 
        id="precio"
        placeholder="Precio servicio"
        value="<?php echo $servicio->precio ?>"
        />
</div>