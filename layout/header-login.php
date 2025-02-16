<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/theme-change@2.0.2/index.js"></script>
    <title>Document</title>
</head>
<body>
     <!-- navbar -->
<header class="sticky top-0 z-50 text-white shadow-md" data-theme="dark">
  <div class="navbar text-white">
    <div class="flex-1">
        <div class="dropdown dropdown-hover">
                <!-- Tombol Username yang memicu dropdown -->
                <button class="btn btn-ghost text-xl"><?php echo strtoupper($_SESSION["username"]) ?></button>
                <!-- Dropdown Menu -->
                <ul class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
                    <li><a href="dashboard.php">Home</a></li>
                    <li><a href="karyawan.php">Karyawan</a></li>
                    <li><a href="pinjaman.php">Pinjaman</a></li>
                    <li><a href="simpanan.php">Simpanan</a></li>
                </ul>
            </div>
        </div>
    
    <!-- Tombol Menu Dropdown Tengah -->
    <div class="flex justify-center items-center ">
    
    <!-- Pencarian dan Avatar (user profile) di sisi kanan -->
    <div class="flex-none gap-96">
      <!-- Pencarian -->
      <div class="form-controll">
      </div>
  
      <!-- Avatar Dropdown -->
      <div class="dropdown dropdown-end">
        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
          <div class="w-10 rounded-full">
            <img
              alt="Tailwind CSS Navbar component"
              src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
          </div>
        </div>
        <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
          <li>
              <form action="dashboard.php" method="POST">
                  <button type="submit" name="logout" class="btn btn-ghost w-full text-left">Logout</button>
              </form>
      </div>
    </div>
  </div>
  

  <script>
        // Mengaktifkan dropdown secara manual dengan event listener
        document.getElementById('username-btn').addEventListener('click', function () {
            let dropdown = document.querySelector('.dropdown-content');
            dropdown.classList.toggle('hidden');
        });
    </script>
</header>
</body>
</html>