<?php require 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Get Queue Number</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-blue-900 to-indigo-950 min-h-screen flex items-center justify-center">
    <div class="text-center bg-white/10 backdrop-blur-xl p-10 rounded-3xl shadow-2xl">
        <h1 class="text-6xl font-black mb-8 text-white">TAKE A NUMBER</h1>
        
        <form method="POST" action="generate.php" class="space-y-6">
            <div>
                <input type="tel" name="phone" maxlength="11" placeholder="09xxxxxxxxx (optional for SMS alert)"
                       class="w-full p-5 text-xl rounded-2xl bg-white/20 text-white placeholder:text-white/70 focus:outline-none focus:ring-4 focus:ring-yellow-400">
                <p class="text-sm text-white/60 mt-2">Enter phone for instant SMS when called</p>
            </div>
            
            <button type="submit"
                    class="w-full bg-yellow-400 hover:bg-yellow-300 text-black text-5xl font-bold py-10 rounded-3xl shadow-2xl transition">
                GET QUEUE NUMBER
            </button>
        </form>
    </div>
</body>
</html>