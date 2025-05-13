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

function confirmarEliminacionEvento(eventoId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Quieres eliminar el evento con ID: ${eventoId}? Esta acción no se puede deshacer.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar evento',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = `../controllers/eliminar_evento.php?evento_id=${eventoId}`;
        }
    });
}

