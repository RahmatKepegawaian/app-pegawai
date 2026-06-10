<?php
  session_start();
  session_destroy();
  echo "<script>alert('Anda telah keluar dari aplikasi'); window.location = 'login'</script>";
?>
