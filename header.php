<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="./css/output.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
</head>
<body>
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
    }

</style>
<nav class="fixed top-0 z-50 flex items-center justify-between w-full py-4 px-6 md:px-16 lg:px-24 xl:px-32 backdrop-blur text-white text-sm">
    <a href="https://github.com/ZXH2y">
        <h3 class ="text-md text-gold-soft font-semibold tracking-wide">Net Present Value</h3>
    </a>

    <div class="hidden md:flex items-center gap-8 transition duration-500">
        <a href="home.php" class="hover:text-amber-900 transition">
            Home
        </a>
        <a href="index.php" class="hover:text-yellow-500 transition">
            Hitung NPV
        </a>
        <a href="/stories" class="hover:text-yellow-500 transition">
            Stories
        </a>
        <a href="/pricing" class="hover:text-yellow-500 transition">
            Pricing
        </a>
    </div>

    <button class="hidden md:block px-6 py-2.5 bg-green-600 hover:bg-amber-400 active:scale-95 transition-all rounded-full">
        Hitung NPV
    </button>
    <button id="open-menu" class="md:hidden active:scale-90 transition">
        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-menu-icon lucide-menu"><path d="M4 5h16"/><path d="M4 12h16"/><path d="M4 19h16"/></svg>
    </button>
</nav>
<div id="mobile-navlinks" class="fixed inset-0 z-[100] bg-black/40 text-white backdrop-blur flex flex-col items-center justify-center text-lg gap-8 md:hidden transition-transform duration-300 -translate-x-full">
    <a href="home.php">
        Home
    </a>
    <a href="/products">
        about
    </a>
    <a href="/stories">
        Stories
    </a>
    <a href="/pricing">
        Pricing
    </a>
    <button id="close-menu" class="active:ring-3 active:ring-white aspect-square size-10 p-1 items-center justify-center bg-purple-600 hover:bg-purple-700 transition text-white rounded-md flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x-icon lucide-x"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
    </button>
</div>
<script>
    const openMenu = document.getElementById("open-menu");
    const closeMenu = document.getElementById("close-menu");
    // const navlinks = document.getElementById("mobile-navlinks");

    const openMenuHandler = () => {
        navlinks.classList.remove("-translate-x-full")
        navlinks.classList.add("translate-x-0")
    }

    const closeMenuHandler = () => {
        navlinks.classList.remove("translate-x-0")
        navlinks.classList.add("-translate-x-full")
    }

    openMenu.addEventListener("click", openMenuHandler);
    closeMenu.addEventListener("click", closeMenuHandler);
</script>
</body>