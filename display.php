<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Display</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Digital-7&display=swap');
    </style>
</head>
<body class="bg-black text-white min-h-screen flex flex-col items-center justify-center">
    <div class="text-center w-full max-w-5xl">
        <h1 class="text-6xl font-bold mb-8 text-red-500">NOW SERVING</h1>
        
        <div id="current" class="text-[220px] font-black text-green-400 tracking-widest font-mono" style="font-family: 'Digital-7', sans-serif;">
            A000
        </div>

        <div class="mt-12">
            <h2 class="text-4xl mb-6">Waiting</h2>
            <div id="waiting" class="grid grid-cols-3 gap-6 text-5xl"></div>
        </div>
    </div>

    <script>
        let lastServing = "";

        async function updateDisplay() {
            const res = await fetch('get_status.php');
            const data = await res.json();

            // Update current
            document.getElementById('current').textContent = data.current || '---';

            // Update waiting list
            let html = '';
            data.waiting.forEach(num => {
                html += `<div class="bg-gray-900 p-6 rounded-2xl">${num}</div>`;
            });
            document.getElementById('waiting').innerHTML = html || '<p class="text-gray-500">No one waiting</p>';

            // Play beep ONLY when number changes
            if (data.current && data.current !== lastServing) {
                playBeep();
                lastServing = data.current;
            }
        }

        function playBeep() {
            const audio = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audio.createOscillator();
            osc.type = 'sine';
            osc.frequency.value = 900;
            osc.connect(audio.destination);
            osc.start();
            setTimeout(() => osc.stop(), 300);
        }

        // Update every 3 seconds
        setInterval(updateDisplay, 3000);
        updateDisplay(); // initial load
    </script>
</body>
</html>