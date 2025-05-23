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

$evento_id = filter_input(INPUT_GET, 'evento_id', FILTER_VALIDATE_INT);
$organizador_id = $_SESSION['usuario_id'];

if (!$evento_id) {
    $_SESSION['error'] = "Evento no válido.";
    header("Location: ../views/mis_eventos.php");
    exit;
}

$stmt_check = $connection->prepare("SELECT id FROM evento WHERE id = ? AND organizador_id = ?");
$stmt_check->bind_param("ii", $evento_id, $organizador_id);
$stmt_check->execute();
$resultado = $stmt_check->get_result();
$stmt_check->close();

if ($resultado->num_rows === 0) {
    $_SESSION['error'] = "No tienes permiso para eliminar este evento.";
    header("Location: ../views/mis_eventos.php");
    exit;
}

$sql_correo = "SELECT u.correo, u.nombre 
               FROM inscripcion i
               JOIN usuario u ON i.usuario_id = u.id
               WHERE i.evento_id = ? AND i.estado = 'aceptada'";
$stmt_mail = $connection->prepare($sql_correo);
$stmt_mail->bind_param("i", $evento_id);
$stmt_mail->execute();
$result = $stmt_mail->get_result();
$stmt_mail->close();

while ($user = $result->fetch_assoc()) {
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
        $mail->Subject = 'Evento cancelado';
        $mail->Body = "Hola {$user['nombre']},\n\nEl organizador ha cancelado un evento al que estabas inscrito.\n\nGracias por usar ActivaTuJuego.";

        $mail->send();
    } catch (Exception $e) {
        error_log("Fallo al enviar a {$user['correo']}: {$mail->ErrorInfo}");
    }
}

$stmt_del_inscripciones = $connection->prepare("DELETE FROM inscripcion WHERE evento_id = ?");
$stmt_del_inscripciones->bind_param("i", $evento_id);
$stmt_del_inscripciones->execute();
$stmt_del_inscripciones->close();

$stmt_del_evento = $connection->prepare("DELETE FROM evento WHERE id = ?");
$stmt_del_evento->bind_param("i", $evento_id);
$stmt_del_evento->execute();
$stmt_del_evento->close();

$_SESSION['success'] = "Evento eliminado y se notificó a los jugadores.";
header("Location: ../views/mis_eventos.php");
exit;
