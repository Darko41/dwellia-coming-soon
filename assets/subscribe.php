<?php

// Email address verification
function isEmail($email) {
    return(preg_match("/^[-_.[:alnum:]]+@((([[:alnum:]]|[[:alnum:]][[:alnum:]-]*[[:alnum:]])\.)+(ad|ae|aero|af|ag|ai|al|am|an|ao|aq|ar|arpa|as|at|au|aw|az|ba|bb|bd|be|bf|bg|bh|bi|biz|bj|bm|bn|bo|br|bs|bt|bv|bw|by|bz|ca|cc|cd|cf|cg|ch|ci|ck|cl|cm|cn|co|com|coop|cr|cs|cu|cv|cx|cy|cz|de|dj|dk|dm|do|dz|ec|edu|ee|eg|eh|er|es|et|eu|fi|fj|fk|fm|fo|fr|ga|gb|gd|ge|gf|gh|gi|gl|gm|gn|gov|gp|gq|gr|gs|gt|gu|gw|gy|hk|hm|hn|hr|ht|hu|id|ie|il|in|info|int|io|iq|ir|is|it|jm|jo|jp|ke|kg|kh|ki|km|kn|kp|kr|kw|ky|kz|la|lb|lc|li|lk|lr|ls|lt|lu|lv|ly|ma|mc|md|mg|mh|mil|mk|ml|mm|mn|mo|mp|mq|mr|ms|mt|mu|museum|mv|mw|mx|my|mz|na|name|nc|ne|net|nf|ng|ni|nl|no|np|nr|nt|nu|nz|om|org|pa|pe|pf|pg|ph|pk|pl|pm|pn|pr|pro|ps|pt|pw|py|qa|re|ro|ru|rw|sa|sb|sc|sd|se|sg|sh|si|sj|sk|sl|sm|sn|so|sr|st|su|sv|sy|sz|tc|td|tf|tg|th|tj|tk|tm|tn|to|tp|tr|tt|tv|tw|tz|ua|ug|uk|um|us|uy|uz|va|vc|ve|vg|vi|vn|vu|wf|ws|ye|yt|yu|za|zm|zw)$|(([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5])\.){3}([0-9][0-9]?|[0-1][0-9][0-9]|[2][0-4][0-9]|[2][5][0-5]))$/i", $email));
}

if($_POST) {

    // ============================================
    // CONFIGURATION SECTION - EDIT THESE VALUES
    // ============================================

    // Your main email where all form submissions will go
    $emailTo = 'noreply@iterials.com'; //

    // Your website/company name
    $siteName = 'Dwellia';

    // ============================================
    // END CONFIGURATION
    // ============================================

    $subscriber_email = trim($_POST['email']);

    // Server-side validation
    if(empty($subscriber_email)) {
        $array = array();
        $array['valid'] = 0;
        $array['message'] = 'Unesite Vašu email adresu.';
        echo json_encode($array);
        exit;
    }

    if(!isEmail($subscriber_email)) {
        $array = array();
        $array['valid'] = 0;
        $array['message'] = 'Unesite validnu email adresu.';
        echo json_encode($array);
        exit;
    }

    // Special subject for easy filtering in Titan
    $subject = '[DWELLIA-FORM] Nova prijava: ' . $subscriber_email;

    // Detailed email body
    $body = "NOVA PRIJAVA ZA DWELLIA PLATFORMU\n";
    $body .= "========================================\n\n";
    $body .= "📧 Email: " . $subscriber_email . "\n";
    $body .= "📅 Datum prijave: " . date('d.m.Y H:i:s') . "\n";
    $body .= "🌐 IP adresa: " . $_SERVER['REMOTE_ADDR'] . "\n";
    $body .= "🖥️ User Agent: " . $_SERVER['HTTP_USER_AGENT'] . "\n\n";
    $body .= "========================================\n";
    $body .= "Ovo je automatska poruka sa Dwellia Coming Soon sajta.\n";
    $body .= "Korisnik se prijavio za obaveštenje o lansiranju platforme.\n";
    $body .= "========================================\n\n";
    $body .= "Akcija: Dodajte u mailing listu za lansiranje.\n";

    // Headers with identifying information
    $headers = "From: Dwellia Website <noreply@yourdomain.com>\r\n"; // ← SAME AS $emailTo
    $headers .= "Reply-To: " . $subscriber_email . "\r\n";
    $headers .= "X-Dwellia-Form: subscription\r\n";
    $headers .= "X-Dwellia-Source: coming-soon-page\r\n";
    $headers .= "X-Priority: 3\r\n";
    $headers .= "X-Mailer: Dwellia PHP Form v1.0\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    // Send email
    if(mail($emailTo, $subject, $body, $headers)) {
        $array = array();
        $array['valid'] = 1;
        $array['message'] = 'Hvala Vam na interesovanju! Obavestićemo Vas kada budemo spremni.';
        echo json_encode($array);
    } else {
        $array = array();
        $array['valid'] = 0;
        $array['message'] = 'Došlo je do greške pri slanju. Pokušajte ponovo.';
        echo json_encode($array);
    }

} else {
    $array = array();
    $array['valid'] = 0;
    $array['message'] = 'Nema podataka za slanje.';
    echo json_encode($array);
}

?>