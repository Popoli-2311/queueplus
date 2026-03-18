<?php require 'config.php'; 
$queue = $_GET['queue'] ?? '';
if (!$queue) die('Missing queue number');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Live Queue Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Digital-7&display=swap');
        body { font-family: system-ui, sans-serif; }
    </style>
</head>
<body class="bg-zinc-950 text-white min-h-screen flex flex-col items-center justify-center p-4">
    <div class="w-full max-w-md mx-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-4xl font-bold text-yellow-400">Queue Tracker</h1>
            <div class="text-xs bg-zinc-800 px-4 py-2 rounded-full">Live</div>
        </div>

        <div class="bg-zinc-900 rounded-3xl p-8 text-center shadow-2xl">
            
            <!-- Your Number -->
            <p class="text-sm text-zinc-400 mb-2">YOUR QUEUE NUMBER</p>
            <div id="myNumber" class="text-[120px] leading-none font-black text-yellow-400 tracking-widest">
                A000
            </div>

            <!-- Now Serving -->
            <div class="mt-10 bg-emerald-900/70 rounded-2xl p-6">
                <p class="text-emerald-400 text-sm uppercase tracking-widest">NOW SERVING</p>
                <div id="currentServing" class="text-7xl font-black text-emerald-400">A000</div>
            </div>

            <!-- Your Position -->
            <div class="mt-8 bg-orange-900/50 rounded-2xl p-6">
                <p class="text-orange-400 text-sm">YOUR POSITION IN LINE</p>
                <div id="position" class="text-6xl font-bold text-orange-400">1st</div>
            </div>

            <!-- Waiting List -->
            <div class="mt-10">
                <h3 class="text-xl mb-4 text-left">Next in line</h3>
                <div id="waitingList" class="space-y-3 text-left"></div>
            </div>
        </div>

        <!-- Status Message -->
        <div id="statusMsg" class="mt-8 text-center text-2xl font-medium min-h-[60px]"></div>

        <!-- Footer -->
        <div class="text-center mt-12 text-zinc-500 text-sm">
            Refreshing every 4 seconds • Powered by your queue system
        </div>
    </div>

    <script>
        const queueNum = "<?php echo $queue; ?>";

        async function updateTracker() {
            try {
                const res = await fetch(`get_track_status.php?queue=${queueNum}`);
                const data = await res.json();

                // Update UI
                document.getElementById('myNumber').textContent = queueNum;
                document.getElementById('currentServing').textContent = data.current || '---';
                document.getElementById('position').textContent = data.position ? data.position + 'th' : '—';

                // Waiting list
                let html = '';
                if (data.waiting && data.waiting.length > 0) {
                    data.waiting.forEach(n => {
                        html += `<div class="bg-zinc-800 p-4 rounded-2xl text-xl flex items-center">
                                    <span class="flex-1">${n}</span>
                                 </div>`;
                    });
                } else {
                    html = '<div class="bg-zinc-800 p-4 rounded-2xl text-zinc-400">No one waiting</div>';
                }
                document.getElementById('waitingList').innerHTML = html;

                // Status message
                const msgEl = document.getElementById('statusMsg');
                if (data.myStatus === 'serving') {
                    msgEl.innerHTML = '🎉 <span class="text-emerald-400 font-bold">YOUR TURN NOW!</span><br>Please go to the counter';
                    msgEl.classList.add('animate-pulse');
                } else if (data.myStatus === 'completed') {
                    msgEl.innerHTML = '✅ Thank you! Your queue is finished.';
                } else {
                    msgEl.innerHTML = '';
                }
            } catch (e) {
                console.log("Connection issue - will retry");
            }
        }

        // Live update
        setInterval(updateTracker, 4000);
        updateTracker(); // first load
    </script>
</body>
</html>