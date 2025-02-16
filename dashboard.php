<?php
    session_start();
    if(isset($_POST["logout"])){
        session_unset();
        session_destroy();
        header("location: index.php");
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koperasi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&family=Roboto&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
  />
  <link rel="stylesheet" href="Style/dashboard.css">
</head>
<body>
    <?php include "layout/header-login.php" ?>
    <main class="bg-gradient-to-r from-blue-100 via-indigo-100 to-purple-100 min-h-screen">
    <div class="container mx-auto px-6 py-16">
        <!-- Welcome Section -->
        <h1 class="text-center text-6xl font-montserrat font-bold text-gray-800 mb-12 animate__animated animate__fadeInUp">
            SELAMAT DATANG, <?php echo strtoupper($_SESSION["username"]) ?>
        </h1>

        <article>
            <div class="flex flex-wrap justify-between items-center mb-16">
                <!-- Description of Koperasi -->
                <p class="max-w-xl text-base leading-relaxed text-gray-800 mb-8 sm:mb-0 font-poppins animate__animated animate__fadeIn animate__delay-1s">
                    <b>Apa itu koperasi ?</b><br><br>
                    Koperasi adalah badan usaha yang beranggotakan orang-orang atau badan hukum koperasi dengan asas kekeluargaan dan prinsip demokrasi ekonomi. Koperasi bertujuan untuk meningkatkan kesejahteraan anggotanya serta memberikan kontribusi bagi pembangunan masyarakat secara umum. Dalam praktiknya, koperasi didirikan dan dijalankan berdasarkan nilai-nilai kebersamaan, solidaritas, dan keadilan. Keanggotaan koperasi bersifat sukarela dan terbuka, dengan pengelolaan yang transparan dan akuntabel. Laba yang diperoleh dari kegiatan usaha koperasi didistribusikan secara adil kepada anggota berdasarkan partisipasi mereka, bukan berdasarkan jumlah modal yang dimiliki.
                </p>
                
                <!-- Image -->
                <img src="image/koperasi.png" alt="koperasi" class="w-full sm:w-1/2 lg:w-1/3 rounded-xl shadow-xl transition-transform transform hover:scale-105 animate__animated animate__fadeIn animate__delay-1s">
            </div>

            <!-- Principles Section -->
            <section class="bg-white shadow-lg rounded-xl p-8 mb-16 max-w-3xl mx-auto">
                <h2 class="text-3xl font-semibold text-center text-gray-800 mb-6 font-poppins">Prinsip Utama Koperasi</h2>
                <p class="text-lg text-gray-600 mb-6">Koperasi memiliki beberapa prinsip utama, antara lain:</p>
                <ol class="list-decimal pl-5 space-y-4 text-gray-800">
                    <li><b>Keanggotaan Sukarela dan Terbuka</b>: Setiap orang dapat menjadi anggota tanpa ada diskriminasi.</li>
                    <li><b>Pengelolaan yang Demokratis</b>: Anggota memiliki hak suara yang setara dalam pengambilan keputusan.</li>
                    <li><b>Partisipasi Ekonomi Anggota</b>: Anggota memberikan kontribusi modal dan menikmati manfaat dari hasil usaha.</li>
                    <li><b>Otonomi dan Kemandirian</b>: Koperasi memiliki kemandirian dalam pengelolaan dan pengambilan keputusan.</li>
                    <li><b>Pendidikan, Pelatihan, dan Informasi</b>: Koperasi memberikan edukasi kepada anggotanya agar dapat berkontribusi secara maksimal.</li>
                    <li><b>Kerjasama Antar Koperasi</b>: Koperasi bekerja sama untuk memperkuat gerakan koperasi secara keseluruhan.</li>
                    <li><b>Kepedulian terhadap Komunitas</b>: Koperasi aktif dalam pembangunan sosial dan ekonomi masyarakat sekitarnya.</li>
                </ol>
            </section>

            <!-- Simpanan Section -->
            <section class="bg-gray-200 p-8 shadow-lg rounded-xl max-w-4xl mx-auto">
                <h2 class="text-4xl font-semibold text-center text-gray-800 mb-6 font-poppins">Jenis Simpanan</h2>
                <p class="text-base text-gray-600 leading-relaxed mb-8 font-poppins">
                    Sebelum kita membahas lebih jauh mengenai jenis-jenis simpanan yang ada dalam koperasi, penting untuk memahami bahwa simpanan anggota merupakan salah satu aspek penting dalam operasional koperasi. Simpanan ini tidak hanya berfungsi sebagai modal yang mendukung kegiatan koperasi, tetapi juga sebagai sarana untuk memberikan manfaat kepada anggota. Dalam koperasi, terdapat beberapa jenis simpanan yang masing-masing memiliki tujuan dan peranannya sendiri. Berikut adalah penjelasan mengenai simpanan pokok, simpanan wajib, dan simpanan sukarela, yang menjadi dasar dari sistem simpanan di koperasi.
                </p>

                <!-- Simpanan Pokok -->
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 font-poppins">Simpanan Pokok</h3>
                <p class="text-base text-gray-600 leading-relaxed mb-8 font-poppins">
                    Simpanan pokok adalah sejumlah dana yang wajib disetorkan oleh setiap anggota koperasi sebagai bentuk partisipasi awal dalam pembentukan modal koperasi. Simpanan ini bersifat satu kali dan tidak dapat ditarik kembali selama menjadi anggota koperasi. Simpanan pokok menjadi salah satu bentuk tanggung jawab anggota untuk mendukung kelangsungan dan perkembangan koperasi, serta sebagai simbol keterikatan anggota terhadap koperasi yang bersangkutan.
                </p>

                <!-- Simpanan Wajib -->
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 font-poppins">Simpanan Wajib</h3>
                <p class="text-base text-gray-600 leading-relaxed mb-8 font-poppins">
                    Simpanan wajib adalah simpanan yang harus disetorkan oleh anggota koperasi secara berkala, biasanya setiap bulan, sesuai dengan ketentuan yang ditetapkan oleh koperasi. Simpanan ini bersifat wajib bagi setiap anggota selama masih menjadi anggota koperasi. Dana yang terkumpul dari simpanan wajib digunakan untuk mendukung operasional koperasi dan memberikan manfaat kepada seluruh anggota, baik dalam bentuk pelayanan maupun pembagian hasil usaha.
                </p>

                <!-- Simpanan Sukarela -->
                <h3 class="text-2xl font-semibold text-gray-800 mb-4 font-poppins">Simpanan Sukarela</h3>
                <p class="text-base text-gray-600 leading-relaxed mb-8 font-poppins">
                    Simpanan sukarela adalah simpanan yang dapat disetorkan oleh anggota koperasi sesuai dengan keinginan dan kemampuan masing-masing. Simpanan ini bersifat fleksibel dan tidak terikat pada kewajiban atau waktu tertentu. Meskipun tidak diwajibkan, simpanan sukarela memberikan kesempatan bagi anggota untuk meningkatkan saldo simpanannya, yang dapat digunakan untuk mendapatkan manfaat lebih dari koperasi, seperti bunga atau pembagian hasil usaha.
                </p>
            </section>
        </article>
    </div>
</main>

    <?php include "layout/footer.html" ?>

</body>
</html>