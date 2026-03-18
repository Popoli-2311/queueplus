<?php
require 'config.php';

$phone = $_POST['phone'] ?? null;
$queue_num = getNextQueueNumber($conn);

$stmt = $conn->prepare("INSERT INTO queue (queue_number, customer_phone, status) VALUES (?, ?, 'waiting')");
$stmt->bind_param("ss", $queue_num, $phone);
$stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Queue Number</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white min-h-screen flex items-center justify-center">
    <div class="text-center">
        <p class="text-3xl mb-4">Your queue number is</p>
        <div class="text-[160px] font-black text-yellow-400 tracking-widest"><?php echo $queue_num; ?></div>

        <!-- QR for Live Tracking -->
        <div class="mt-8">
            <p class="text-xl mb-4">Scan for Live Tracking on your phone</p>
            <!-- Replace the old QR img line with this -->
<img src="https://api.qrserver.com/v1/create-qr-code/?size=280x280&data=<?php 
    192.168.1.110 = $_SERVER['SERVER_ADDR'];  // or manually put your PC IP
    echo urlencode('http://'.$localIP.'/queue-system/track.php?queue='.$queue_num); 
?>" alt="QR Code" class="mx-auto shadow-2xl rounded-2xl">
        </div>

        <div class="mt-12 space-x-6">
            <!-- Print Button for Thermal Printer -->
            <button onclick="printThermalTicket()" 
                    class="bg-white text-black px-10 py-5 rounded-2xl text-2xl font-bold hover  :scale-105 transition">
                🖨️ Print Ticket (Thermal Printer)
            </button>
            
            <a href="track.php?queue=<?php echo $queue_num; ?>" 
               class="inline-block bg-green-500 px-10 py-5 rounded-2xl text-2xl font-bold hover:scale-105 transition">
               📱 Live Track on Phone
            </a>
        </div>

        <p class="mt-12 text-gray-400">Please wait for your number to be called</p>
    </div>

    <!-- Hidden Thermal Ticket Template -->
    <script>
    function printThermalTicket() {
        const ticketHTML = `
            <div style="width: 300px; margin: 0 auto; font-family: monospace; font-size: 14px; padding: 10px; text-align: center; line-height: 1.4;">
                <h1 style="font-size: 22px; font-weight: bold;">QUEUE TICKET</h1>
                <div style="font-size: 55px; font-weight: bold; letter-spacing: 6px; margin: 15px 0;">
                    <?php echo $queue_num; ?>
                </div>
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?php echo urlencode('http://'.$_SERVER['HTTP_HOST'].'/queue-system/track.php?queue='.$queue_num); ?>" width="200" style="margin:10px auto; display:block;">
                <p style="font-size: 13px;">Scan QR for LIVE tracking</p>
                <p style="font-size: 12px; margin-top: 20px;">Date: <?php echo date('M d, Y h:i A'); ?></p>
                <p style="font-size: 10px; margin-top: 30px;">Thank you! Please wait patiently.</p>
            </div>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html><head><title>Ticket</title></head><body style="margin:0;padding:0;">${ticketHTML}</body></html>
        `);
        printWindow.document.close();
        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 300);
    }
    </script>
</body>
</html>