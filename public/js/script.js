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




