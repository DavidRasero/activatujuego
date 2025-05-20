function confirmarEliminacion(id, nombre) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres eliminar al usuario: ${nombre}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../controllers/eliminar_usuario.php?id=${id}`;
        }
    });
}

function confirmarFinalizacionEvento(eventoId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Una vez finalizado, no podrás aceptar más jugadores.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, finalizar evento',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#198754',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '../controllers/finalizar_evento.php?evento_id=' + eventoId;
        }
    });
}

function confirmarEliminacionDeporte(id, nombre) {
    Swal.fire({
        title: '¿Eliminar deporte?',
        text: `¿Estás seguro de que deseas eliminar el deporte "${nombre}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirige al controlador PHP
            window.location.href = `../controllers/eliminar_deporte.php?id=${id}`;
        }
    });
}

function confirmarEliminacionEvento(eventoId, deporte) {
    Swal.fire({
        title: '¿Eliminar evento?',
        text: `¿Estás seguro de que deseas eliminar el evento de "${deporte}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../controllers/eliminar_evento.php?evento_id=${eventoId}`;
        }
    });
}

function confirmarEliminacionCentro(id, nombre) {
    Swal.fire({
        title: '¿Eliminar centro?',
        text: `¿Estás seguro de que deseas eliminar el centro "${nombre}"? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../controllers/eliminar_centro.php?id=${id}`;
        }
    });
}







