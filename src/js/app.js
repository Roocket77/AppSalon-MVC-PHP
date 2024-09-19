let paso = 1;
const pasoInicial = 1;
const pasoFinal= 3;

const cita = {
    id : '',
    nombre : '',
    fecha : '',
    hora : '',
    servicios : []
}


document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
 });

 function iniciarApp(){
    mostrarSeccion();//Muestra y oculta las secciones
    tabs(); // cambia de seccion cuando se presionen los tabs
    botonesPaginador(); // Agrega o quita los botones del paginador
    paginaSiguiente();
    paginaAnterior();

    consultarAPI(); // CONSULTAR LA API EN EL BACKEND DE PHP

    idCliente(); //
    nombreCliente(); //Añade el nombre del cliente al objeto de cita
    seleccionarFecha(); //Añade fecha de la cita al objeto
    seleccionarHora();//Añade hora de la cita al objeto

    mostrarResumen(); //muestra resumen de cita con el servicio


}

function botonesPaginador(){
    const paginaSiguiente = document.querySelector('#siguiente');
    const paginaAnterior = document.querySelector('#anterior');

    if(paso === 1){
        paginaAnterior.classList.add('ocultar-p');
        paginaSiguiente.classList.remove('ocultar-p');
    } else if(paso === 3){
        paginaAnterior.classList.remove('ocultar-p');
        paginaSiguiente.classList.add('ocultar-p');

        // mostrarResumen();

    } else{
        paginaAnterior.classList.remove('ocultar-p');
        paginaSiguiente.classList.remove('ocultar-p');
    }
    mostrarSeccion();
}

function mostrarSeccion(){
    //Ocultar la seccion que tenga la clase de mostrar
    const seccionAnterior = document.querySelector('.mostrar');
    if(seccionAnterior){
        seccionAnterior.classList.remove('mostrar');
    }
    
    //Seccionar la seccion con el paso
    const pasoSeccion = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSeccion);
    seccion.classList.add('mostrar');

    //Quita la clase de tab actual al tab anterior
    const tabAnterior= document.querySelector('.actual');
    tabAnterior.classList.remove('actual');

    
    //Resalta el tab actual
    const tab = document.querySelector(`[data-paso="${paso}"]`);
    tab.classList.add('actual');
}

function tabs(){
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach(boton =>{
        boton.addEventListener('click', function(e){
            paso = parseInt( e.target.dataset.paso);
            mostrarSeccion();
            botonesPaginador();
            mostrarResumen();

        });
    })
}

function paginaSiguiente(){
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click' ,function(){
        if(paso >= pasoFinal) return;
        paso++;
        botonesPaginador();
        if(paso === 3){
            mostrarResumen();
        }
    })
}

function paginaAnterior(){
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click' ,function(){
        if(paso <= pasoInicial) return;
        paso--;
        botonesPaginador();
    })
}
//------------------------------------------------

async function consultarAPI(){
    try {
        const url = '/api/servicios';
        const resultado = await fetch(url);
        const servicios = await resultado.json();
        // console.log(servicios);
        mostrarServicios(servicios);

    } catch (error) {
        console.log(error);

    }

}

function mostrarServicios(servicios){
    servicios.forEach(servicio => {
        const {id, nombre, precio} = servicio;

        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;
        
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$ ${precio}`;
        
        const serviciosDiv = document.createElement('DIV');
        serviciosDiv.classList.add('servicio');
        serviciosDiv.dataset.idServicio = id;
        serviciosDiv.onclick = function(){
            seleccionarServicio(servicio);
        }

        serviciosDiv.appendChild(nombreServicio);
        serviciosDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(serviciosDiv);

    });
}

function seleccionarServicio(servicio){
    const { id } = servicio;
    const { servicios } = cita;

    //Identificar al elemento que se le da click
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    //Comprobar si un servicio ya fue agregado
    if( servicios.some( agregado => agregado.id === id ) ){
        //Eliminarlo
        cita.servicios = servicios.filter( agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');

    }else{
        //Agregarlo
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }

    // console.log(cita);
}
function nombreCliente(){
    cita.nombre = document.querySelector('#nombre').value;
    
}
function idCliente(){
    cita.id = document.querySelector('#id').value;
    
}
function seleccionarFecha(){
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e){
        const dia = new Date(e.target.value).getUTCDay();//getUTCDay, se puede obtener el numero de dia y sacar que dia esta seleccionando
        
        //Controlas que dias no estan disponibles
        if([6, 0].includes(dia)){
            e.target.value = '';
            mostrarAlerta('Dia seleccionado no disponible', 'error', '.formulario');


        }else{
            cita.fecha = inputFecha.value;
        }
        

    });
}

function seleccionarHora(){
    const inputHora =document.querySelector('#hora');
    inputHora.addEventListener('input', function(e){
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];
        if(hora <10 || hora >18){
            e.target.value = '';
            mostrarAlerta('Hora no disponible', 'error', '.formulario');
        }else{
            cita.hora = e.target.value;
        }
    });
}

function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){
    //Previene que se genere mas de 1 alerta
    const alertaPrevia = document.querySelector('.alerta');
    if(alertaPrevia){
        alertaPrevia.remove();
    }

    //Scripting para crear la alerta
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje;
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);
    const formulario = document.querySelector(elemento);
    formulario.appendChild(alerta);
    if(desaparece){
    //Eliminar alerta luego de 3s
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
   

}
function mostrarResumen(){
    const resumen = document.querySelector('.contenido-resumen');
    //limpiar contenido del resumen
    while(resumen.firstChild){
        resumen.removeChild(resumen.firstChild);
    }

    if(Object.values(cita).includes('') || cita.servicios.length === 0){
        mostrarAlerta('Faltan datos de servicio, fecha u hora', 'error', '.contenido-resumen', false);
     return;
    }

    //formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;

    //heading para servicios en resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios: ';
    resumen.appendChild(headingServicios);

    //iterando y mostrando los servicios
    servicios.forEach(servicio => {
        const { id, precio, nombre } = servicio;

        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const textoServicio = document.createElement('P');
        textoServicio.textContent = nombre;

        const precioServ = document.createElement('P');
        precioServ.innerHTML = `<span>Precio: </span> $${precio}`;

        contenedorServicio.appendChild(textoServicio);
        contenedorServicio.appendChild(precioServ);

        resumen.appendChild(contenedorServicio);
    })
    const headingInfo = document.createElement('H3');
    headingInfo.textContent = 'Resumen de Cita';
    resumen.appendChild(headingInfo);


    const contenedorInfo = document.createElement('DIV');
    contenedorInfo.classList.add('contenedor-info');

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre: </span> ${nombre}`;

    //Formatear fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate();
    const year = fechaObj.getFullYear();
    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'}
    const fechaUTC = new Date( Date.UTC(year, mes, dia ));
    const fechaFormateada = fechaUTC.toLocaleDateString('es-AR', opciones);
    // console.log(fechaFormateada);


    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha: </span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora: </span> ${hora} Horas`;

    //Boton para crear una cita
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;


    contenedorInfo.appendChild(nombreCliente);
    contenedorInfo.appendChild(fechaCita);
    contenedorInfo.appendChild(horaCita);
    resumen.appendChild(contenedorInfo);

    resumen.appendChild(botonReservar);

}

async function reservarCita(){
    const { id, nombre, fecha, hora, servicios} = cita;
    const idServicio = servicios.map( servicio => servicio.id );

    const datos = new FormData();
    datos.append('usuarioId', id);
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('servicios', idServicio);
    // console.log([...datos]);
    
    // return;
    //Peticion hacia la api
    
    try {
        const url = '/api/citas';

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        const resultado = await respuesta.json();


        if(resultado.resultado){
            Swal.fire({
                icon: 'success',
                title: 'Cita creada',
                text: 'Tu cita fue creada correctamente',
                button: 'OK'
            }).then( () =>{
                setTimeout(() => {
                    window.location.reload();
                }, 1500); 
            })
        }
    } catch (error) {
        
        Swal.fire({
        icon: "error",
        title: "Error",
        text: "Hubo un error al guardar la cita",
        button: 'OK'
        });
        console.log(error);
    }
    
    


    
}