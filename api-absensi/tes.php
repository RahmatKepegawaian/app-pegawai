<?=
include "../conf/conf.php";
$url=$_SERVER['REQUEST_URI'];
header("Refresh: 5; URL=$url"); 
$QueryString="SELECT USERINFO.Badgenumber, CHECKTIME, CHECKTYPE FROM CHECKINOUT left join USERINFO on USERINFO.USERID=CHECKINOUT.USERID";  
   
 $Connect = odbc_connect( 'ekin', 'ekin', '');  
 $Result = odbc_exec( $Connect, $QueryString ); 
 while( odbc_fetch_row( $Result ) )  
 {  
		$id  = odbc_result($Result,"Badgenumber");  
        $tanggal  = odbc_result($Result,"CHECKTIME");  
        $status  = odbc_result($Result,"CHECKTYPE");  
		$cek=getOne("select id from log where user='$id' and tanggal='$tanggal' and status='$status'");
		$thn = FormatTgl('Y', $tanggal);
		if ($cek =='' and $tanggal >='2019' ){
		$autonumber = nokiamat1('id', 'log');
		simpanLog("insert into log set id='$autonumber', user='$id', tanggal='$tanggal', status='$status'");
		}else{
		}
 }
?> 