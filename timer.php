<?php 
    $server     = "localhost";      //sesuaikan dengan nama server 
    $username   = "root";           //sesuaikan dengan username server, username default adalah root 
    $password   = "";               //sesuaikan dengan password server, apabila tidak ada dikosongi saja 
    $database   = "test_timer";    //sesuaikan dengan nama database yang sudah dibuat
     
	// Koneksi dan memilih database di server 
	$con=mysqli_connect($server,$username,$password,$database);
	// Check connection
	if (mysqli_connect_errno())
	  {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	  }


    //untuk memulai session
    session_start();
     
    //set session dulu dengan nama $_SESSION["mulai"]
    if (isset($_SESSION["mulai"])) { 
        //jika session sudah ada
        $telah_berlalu = time() - $_SESSION["mulai"];
    } else { 
        //jika session belum ada
        $_SESSION["mulai"]  = time();
        $telah_berlalu      = 0;
    } 
	
	$sql    = mysqli_query($con,"select * from timer");   
    $data   = mysqli_fetch_array($sql);
 
    $temp_waktu = ($data['waktu']*60) - $telah_berlalu; //dijadikan detik dan dikurangi waktu yang berlalu
    $temp_menit = (int)($temp_waktu/60);                //dijadikan menit lagi
    $temp_detik = $temp_waktu%60;                       //sisa bagi untuk detik
     
    if ($temp_menit < 60) { 
        /* Apabila $temp_menit yang kurang dari 60 meni */
        $jam    = 0; 
        $menit  = $temp_menit; 
        $detik  = $temp_detik; 
    } else { 
        /* Apabila $temp_menit lebih dari 60 menit */           
        $jam    = (int)($temp_menit/60);    //$temp_menit dijadikan jam dengan dibagi 60 dan dibulatkan menjadi integer 
        $menit  = $temp_menit%60;           //$temp_menit diambil sisa bagi ($temp_menit%60) untuk menjadi menit
        $detik  = $temp_detik;
    }   
?>

<head>
    <!-- Kita membutuhkan jquery, disini saya menggunakan langsung dari jquery.com, jquery ini bisa didownload dan ditaruh dilocal -->
    <script src="http://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript"></script>
  
    <!-- Script Timer -->
    <script type="text/javascript">
        $(document).ready(function() {
            /** Membuat Waktu Mulai Hitung Mundur Dengan 
                * var detik;
                * var menit;
                * var jam;
            */
            var detik   = <?= $detik; ?>;
            var menit   = <?= $menit; ?>;
            var jam     = <?= $jam; ?>;
                  
            /**
               * Membuat function hitung() sebagai Penghitungan Waktu
            */
            function hitung() {
                /** setTimout(hitung, 1000) digunakan untuk 
                     * mengulang atau merefresh halaman selama 1000 (1 detik) 
                */
                setTimeout(hitung,1000);
  
                /** Jika waktu kurang dari 10 menit maka Timer akan berubah menjadi warna merah */
                if(menit < 10 && jam == 0){
                    var peringatan = 'style="color:red"';
                };
				
                /** Menampilkan Waktu Timer pada Tag #Timer di HTML yang tersedia */
                $('#timer').html(
                    '<h1 align="center"'+peringatan+'>Sisa waktu anda <br />' + jam + ' jam : ' + menit + ' menit : ' + detik + ' detik</h1><hr>'
                );
  
                /** Melakukan Hitung Mundur dengan Mengurangi variabel detik - 1 */
                detik --;
  
                /** Jika var detik < 0
                    * var detik akan dikembalikan ke 59
                    * Menit akan Berkurang 1
                */
                if(detik < 0) {
                    detik = 59;
                    menit --;
  
                   /** Jika menit < 0
                        * Maka menit akan dikembali ke 59
                        * Jam akan Berkurang 1
                    */
                    if(menit < 0) {
                        menit = 59;
                        jam --;
  
                        /** Jika var jam < 0
                            * clearInterval() Memberhentikan Interval dan submit secara otomatis
                        */
                             
                        if(jam < 0) { 
                            clearInterval(hitung); 
                            /** Variable yang digunakan untuk submit secara otomatis di Form */
                            var frmSoal = document.getElementById("frmSoal"); 
                            //alert('Waktu Anda telah habis, Jika ingin mencoba lagi silahkan dihapus dulu SESSION browser anda');
                            frmSoal.submit(); 
                        } 
                    } 
                } 
            }           
            /** Menjalankan Function Hitung Waktu Mundur */
            hitung();
        });
    </script>
</head>

<body>        
    <div id='timer'></div>
    <form action="index.php" id="frmSoal" method='POST' > 
        <!-- Taruh script disini, bisa soal atau sesuai kebutuhan -->
    </form>
</body>