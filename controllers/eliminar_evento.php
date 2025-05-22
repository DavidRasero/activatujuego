<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once('../includes/PHPMailer/src/PHPMailer.php');
require_once('../includes/PHPMailer/src/SMTP.php');
require_once('../includes/PHPMailer/src/Exception.php');

session_start();
require_once('../config/database.php');

if (!isset($_SESSION['usuario_id']) || $_SESSION['tipo'] !== 'organizador') {
    $_SESSION['error'] = "No tienes permiso para realizar esta acción.";
    header("Location: ../index.php");
    exit;
}

if (!isset($_GET['evento_id'])) {
    $_SESSION['error'] = "Evento no válido.";
    header("Location: ../views/mis_eventos.php");
    exit;
}

$evento_id = intval($_GET['evento_id']);
$organizador_id = $_SESSION['usuario_id'];

$sql_check = "SELECT id FROM evento WHERE id = ? AND organizador_id = ?";
$stmt = mysqli_prepare($connection, $sql_check);
mysqli_stmt_bind_param($stmt, "ii", $evento_id, $organizador_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);

if (mysqli_fetch_assoc($resultado)) {
    $sql_correo = "SELECT u.correo, u.nombre 
                   FROM inscripcion i
                   JOIN usuario u ON i.usuario_id = u.id
                   WHERE i.evento_id = ? AND i.estado = 'aceptada'";
    $stmt_mail = mysqli_prepare($connection, $sql_correo);
    mysqli_stmt_bind_param($stmt_mail, "i", $evento_id);
    mysqli_stmt_execute($stmt_mail);
    $result = mysqli_stmt_get_result($stmt_mail);

    while ($user = mysqli_fetch_assoc($result)) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'piesdeplatam959@gmail.com';
            $mail->Password = 'sbdk nrhy xgac ydrh';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('piesdeplatam959@gmail.com', 'ActivaTuJuego');
            $mail->addAddress($user['correo'], $user['nombre']);
            $mail->Subject = 'Cancelación de evento';
            $mail->Body = "Hola {$user['nombre']},\n\nEl organizador ha cancelado un evento al que estabas inscrito.\n\nGracias por usar ActivaTuJuego.";

            $mail->send();
        } catch (Exception $e) {
            echo "Error al enviar correo a {$user['correo']}: {$mail->ErrorInfo}<br>";
        }

    }
    mysqli_stmt_close($stmt_mail);

    $sql_del_inscripciones = "DELETE FROM inscripcion WHERE evento_id = ?";
    $stmt_del = mysqli_prepare($connection, $sql_del_inscripciones);
    mysqli_stmt_bind_param($stmt_del, "i", $evento_id);
    mysqli_stmt_execute($stmt_del);
    mysqli_stmt_close($stmt_del);

    $sql_del_evento = "DELETE FROM evento WHERE id = ?";
    $stmt_evt = mysqli_prepare($connection, $sql_del_evento);
    mysqli_stmt_bind_param($stmt_evt, "i", $evento_id);
    mysqli_stmt_execute($stmt_evt);
    mysqli_stmt_close($stmt_evt);

    $_SESSION['success'] = "Evento eliminado y notificación enviada a los jugadores.";
} else {
    $_SESSION['error'] = "No tienes permiso para eliminar este evento.";
}

mysqli_close($connection);
header("Location: ../views/mis_eventos.php");
exit;
