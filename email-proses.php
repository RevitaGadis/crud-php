<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'vendor/autoload.php';

if (isset($_POST['kirim'])) {

    $email_penerima = filter_var($_POST['email_penerima'], FILTER_VALIDATE_EMAIL);
    $subject        = strip_tags($_POST['subject']);
    $pesan          = $_POST['pesan'];

    if (!$email_penerima) {
        echo "<script>
            alert('Email penerima tidak valid');
            document.location.href = 'email.php';
        </script>";
        exit();
    }

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'tutormubatekno@gmail.com';
        $mail->Password   = 'hekuvjimgpsabydd';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;

        $mail->setFrom('tutormubatekno@gmail.com', 'Tutorial Muba Teknologi');
        $mail->addAddress($email_penerima);
        $mail->addReplyTo('@gmail.com', 'Tutorial Muba Teknologi');

        $mail->Subject = $subject;
        $mail->Body    = $pesan;

        $mail->send();

        echo "<script>
            alert('Email Berhasil Dikirimkan');
            document.location.href = 'email.php';
        </script>";
    } catch (Exception $e) {
        echo "<script>
            alert('Email Gagal Dikirimkan');
            document.location.href = 'email.php';
        </script>";
    }

    exit();
}