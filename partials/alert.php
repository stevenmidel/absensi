<?php if (!empty($_SESSION['alert'])): ?>
    <div id="alertBox"
        class="fixed top-4 right-4 z-50 max-w-sm w-[90%] sm:w-auto">

        <div class="flex gap-3 items-start bg-white
        <?= $_SESSION['alert']['type'] === 'success'
            ? 'border-l-4 border-teal-500'
            : 'border-l-4 border-red-500' ?>
        rounded-2xl shadow-xl p-4 animate-slide-in">

            <div class="text-sm text-gray-700">
                <p class="font-semibold mb-1">
                    <?= htmlspecialchars($_SESSION['alert']['title']) ?>
                </p>
                <p><?= htmlspecialchars($_SESSION['alert']['message']) ?></p>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            const el = document.getElementById('alertBox');
            if (el) el.remove();
        }, 4000);
    </script>
<?php unset($_SESSION['alert']);
endif; ?>

<style>
    @keyframes slide-in {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in {
        animation: slide-in .35s ease-out;
    }
</style>