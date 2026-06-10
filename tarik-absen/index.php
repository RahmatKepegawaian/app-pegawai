<?=
include "conf/conf.php";
$url=$_SERVER['REQUEST_URI'];
header("Refresh: 30; URL=$url"); 
$thn=date('Y');
$QueryString="SELECT USERINFO.Badgenumber, CHECKTIME, CHECKTYPE FROM CHECKINOUT left join USERINFO on USERINFO.USERID=CHECKINOUT.USERID where CHECKTIME LIKE '%$thn%' ORDER BY CHECKTIME DESC";  
   
 $Connect = odbc_connect( $user_odbc, $user_odbc, $pass_odbc);  
 $Result = odbc_exec( $Connect, $QueryString ); 
 while( odbc_fetch_row( $Result ) )  
 {  
		$id  = odbc_result($Result,"Badgenumber");  
        $tanggal  = odbc_result($Result,"CHECKTIME");  
        $status  = odbc_result($Result,"CHECKTYPE");  
		$cek=getOne("select id from log where user='$id' and tanggal='$tanggal' and status='$status'");		
		if ($cek ==''){
		$autonumber = nokiamat('id', 'log');
		simpanLog("insert into log set id='$autonumber', user='$id', tanggal='$tanggal', status='$status'");
		}else{
		}
		//echo $id." ".$tanggal." ".$status."<br>";
 }
?> 