<?php

class main{
    
    public function teszt(){
        
        
        print "ez itt most a teszt";
        print "KisFiam pihenj egy kiocsit, falatozz egy kis szalonnát és sétálj egyet! :)"
    }
    
    
    public function menu(){
        
        
        print "<div id='menu' class='menu'>
            <a href='index.php'>Home</a>
            <a href='stat-php.php'>Terkep</a>
            <a href='logout.php'>Kijelentkezes</a>
            </div>
            
        
        ";
        
        
        
    }
    
    
    public function teszt_osszerak($kerdesek_szama,$also,$felso,$teszt_type){
        
        print "<br>";
        print "Teszt típusa: " . $teszt_type;
        $kerdessor=Array();
        
        $i=1;
        //$kerdessor[]=1480;
        
        
        
        
        if($teszt_type=='sima'){
               $kapcs=new kapcsolat;
                        $db=$kapcs->ujkapcsolat();
    
                    
                               $sql='SELECT * FROM allamvizsga_valaszok WHERE valid=1';
            
                                foreach($db->query($sql) as $row){
            
                                  $kerdessor[]=$row['id'];
            
                                }   
                                
            
             }
        
        
        if($teszt_type=='csak_rossz'){
            print "elindult a csak rosz";
            
                        $kapcs=new kapcsolat;
                        $db=$kapcs->ujkapcsolat();
    
                    
                               $sql='SELECT * FROM allamvizsga_user_stats WHERE user_id="' . $_SESSION['user_id'] . '" AND value=0';
            
                                foreach($db->query($sql) as $row){
            
                                  $kerdessor[]=$row['kerdes_id'];
            
                    
        
                    }
        }
        
        if($teszt_type=='csak_ismeretlen'){
            print "elindult a csak ismeretlen";
            
                        $kapcs=new kapcsolat;
                        $db=$kapcs->ujkapcsolat();
    
                    
                               $sql='SELECT * FROM allamvizsga_valaszok WHERE valid=1';
            
                                foreach($db->query($sql) as $row){
            
                                  $osszes_kerdes[$row['id']]=$row['id'];
            
                                }
                               // $sql='SELECT * FROM allamvizsga_user_stats WHERE user_id=' . $_SESSION['user_id'] . " AND value <> 1";
                                $sql="SELECT kerdes_id,max(value) as value from allamvizsga_user_stats WHERE user_id=" . $_SESSION['user_id'] . " group by kerdes_id ";
                                
                                foreach($db->query($sql) as $row){
            
                                   if($row['value']==0){
                                        $kerdessor[]=$row['kerdes_id'];
                                                         }        
            
                                }
                                
                    foreach($ismert_kerdesek as $value){
                                unset($osszes_kerdes[$value]);
                        
                    }
                    foreach($osszes_kerdes as $value){
                        
                        $osszes_kerdes2[]=$value;
                    }
                    
                    
                            $kerdessor=$osszes_kerdes2;    
                                
        }
        
        $pot_kerdesek_szama=count($kerdessor);
        
        $i=1;
        $szett_elem_szam=$_GET['kerdesszam'];
        //$szett_elem_szam=25;
        $kerdes_szett=Array();
        print "<br>";
        print "Kérdések száma:" . $szett_elem_szam;
          print "<br>";
        print "Potenciális kérdések száma: " . $pot_kerdesek_szama;
        
        if($szett_elem_szam>=$pot_kerdesek_szama){$szett_elem_szam=$pot_kerdesek_szama;}
        while($i<=$szett_elem_szam){
            
            
            $j=rand(0,$pot_kerdesek_szama-1);
            
            if(in_array($kerdessor[$j],$kerdes_szett)){
                
            }else{
                $kerdes_szett[$i]=$kerdessor[$j];
                $i++;
                
            }
            
            
            
        }
        
      // print_r($kerdes_szett);
       unset($kerdessor);
       $kerdessor=$kerdes_szett;
        
        
        $szet_id=$this->kerdessor_konyvel($_SESSION['user_id'],$kerdessor);
        
        print "<div id='szet_id'>Kérdéssor ID: " . $szet_id . "</div>";
        print "<div id='szet_id_num' style='display:none'>" . $szet_id . "</div>";
        
        print "<div id='eredmenyek_mutat' >";
            print "<div class='elert_score'>" . "13" . "</div>";
            print "<div class='kerdes_szam_score'> / " . "25" . "</div>";
        print "</div>";
        $jovalaszok=$this->jovalaszok_leker($kerdessor);
       
       $xml=simplexml_load_file("kerdesek/zarovizsga.xml");
      
        foreach($kerdessor as $i=>$value){
            
            $value=(int)$value;
            $feladat_szama=$xml->table[0]->rows->row[$value]->columns->column[1];
            $feladat_es_megoldas=$xml->table[0]->rows->row[$value]->columns->column[2];
          // $feladat_es_megoldas=utf8_encode($feladat_es_megoldas);
            $feladat_es_megoldas=$feladat_es_megoldas;
            
            $hatar="<DIV>Magyar";
            $hossz=strpos($feladat_es_megoldas,$hatar);
            $lenght=strlen($feladat_es_megoldas);
            
            $csak_feladat=substr($feladat_es_megoldas,0,$hossz);
            $csak_megoldas=substr($feladat_es_megoldas,$hossz,$lenght);
            
            $hatar="Megoldás</SPAN>";
            $hossz=strpos($feladat_es_megoldas,$hatar);
            $lenght=strlen($feladat_es_megoldas);
            $csak_megoldas_betu=trim(str_replace(")","",strip_tags(substr($feladat_es_megoldas,$hossz+22,22))));
           

            
            print "<div id='kerdes_" . $i . "' class='kerdes'> ";
            //print $feladat_szama;
             print $csak_feladat;
                 //print $csak_feladat;          
                      print "<div id='magyarazat_" . $i . "' class='magyarazat'>";
                    
                   print $this->megoldas_cleaner($csak_megoldas);
                    
                    
                    
                     
                    print "</div>";
            
                    print"<div id='user_valasz_" . $i . "' class='user_valasz_div'>";
                            $this->user_valasz_gombok_kiir($value,$csak_megoldas_betu);
                    print "</div>";
                    print "</div>";
            
            
            
            
            
            
          /*  
             print $key . " - " . $value . " - ";
           $myfile = fopen("kerdesek/" . $value . ".txt", "r");
            echo fread($myfile,filesize("kerdesek/" . $value . ".txt"));
            fclose($myfile);
           
                print "<div id='valasz_" . $key . "' class='valasz'>";
                
                    $this->valasz_opciok_general($value,$jovalaszok[$value]);
                print "</div>";
           
           print "<div id='magyarazat_" . $value . "' class='magyarazat'>";
           
                print $this->getmagyarazat($value);
           
           
           print"</div>";
            print "</div>";
            */
        }
        
        
        
        
        print "<table border='0' width='100%'><tr><td width='33%'></td><td width='33%' align='center'>";
        print "<button id='check'>Check</button>";
        print "</td><td width='33%'></td></tr></table>";
        print "<table border='0' width='100%'><tr><td width='33%'></td><td width='33%' align='center'>";
        print "<button id='ujsor'>Új vizsgasor</button>";
        print "</td><td width='33%'></td></tr></table>";
        
        print "<div id='results'>";
        
           foreach($kerdessor as $key=>$value){
                 print $key . " - " ;
                print "<div id='result_" . $value . "'>";
                   print  $value;
                print "</div>";
            
            
            }
            
            
        
        
        print"</div>";
        
        
    }
    
    public function megoldas_cleaner($megoldas){
        
        
        
        $ujmegoldas=str_replace("Magyarázat és Megoldás megtekintése","",$megoldas);
        $ujmegoldas2=str_replace('</tr>','DOMI',$ujmegoldas);
        
        $ujmegoldas=strip_tags($ujmegoldas);
        $ujmegoldas=str_replace("Megoldás","<br><br><u><b>Megoldás</u></b><br>",$ujmegoldas);
        $ujmegoldas=str_replace("Magyarázat","<br><br><u><b>Magyarázat</u></b><br>",$ujmegoldas);
        return $ujmegoldas;
        
        
        
    }
    
    public function user_valasz_gombok_kiir($id,$jovalasz){
        
        $valaszok[]="A";
        $valaszok[]="B";
        $valaszok[]="C";
        $valaszok[]="D";
        $valaszok[]="E";
        $valaszok[]="F";
        $valaszok[]="G";
        $valaszok[]="H";
        //print $jovalasz;
        
        foreach($valaszok as $key=>$value){
        
        if($jovalasz==$value){
            $jovalasz_class=' jovalasz';
        }else{
            $jovalasz_class='';
        }
        print "<div id='user_valasz_" . $value . "_" . $id . "' kerdessorszam=" . $id . " valasz='" . $value . "' class='user_valasz_gomb_class" . $jovalasz_class . "'>";
            print $value;
        print "</div>";
        
        }
        
        
        
    }
    
    
    public function veletlen_generalo($also,$felso){
        
        
        
        $szam=rand($also,$felso);
        
        return $szam;
        
        
        
    }
  
    public function kerdes_opciok_osszerak($betu,$value,$jomegoldas){
        
        $class="class='valasz_opcio";
        if($jomegoldas==$betu){
            
            $class=$class . " jomegoldas";
            //print $jomegoldas;
        }
        $class=$class . "'";
        $divnyitotag="<div id='valasz_opcio_" . $value . "-" . $betu . "' valasz='" . $betu . "' kerdessorszam='" . $value . "' " . $class . ">" . $betu . ")" ;
        $divzarotag="</div>";
        
        if($betu=="A"){
            
            $msg=$divnyitotag;
        }else{
            $msg=$divzarotag . $divnyitotag;
        }
        
        return $msg;
    }
    
    public function valasz_opciok_general($value,$jomegoldas){
        
            
            $kerdesbetuk[]="A";
            $kerdesbetuk[]="B";
            $kerdesbetuk[]="C";
            $kerdesbetuk[]="D";
            $kerdesbetuk[]="E";
            $kerdesbetuk[]="F";
            $kerdesbetuk[]="G";
            $kerdesbetuk[]="H";
            $kerdesbetuk[]="I";
            $kerdesbetuk[]="J";
            //$kerdes_betuk=$kerdesbetuk;
            $kerdes_betuk=array("A)","B)","C)","D)","E)","F)","G)","H)","I)");
            
            //$kerdesbetuk_valsztokkal=array($hatarolo . "A)",$hatarolo . "B)",$hatarolo . "C)",$hatarolo . "D)",$hatarolo . "E)",$hatarolo . "F)",$hatarolo . "G)",$hatarolo . "H)",$hatarolo . "I)");
        
            foreach($kerdesbetuk as $betu){
                
                $kerdesbetuk_valsztokkal[]=$this->kerdes_opciok_osszerak($betu,$value,$jomegoldas);
            }
        
           
            
            
            
            
            $myfile1 = fopen("valaszok/" . $value . ".txt", "r");
            
            
            $valaszok=fread($myfile1,filesize("valaszok/" . $value . ".txt"));
            $ujvalaszok=str_replace($kerdes_betuk,$kerdesbetuk_valsztokkal,$valaszok) . "</div>" ;
          
            echo $ujvalaszok;
            fclose($myfile1);
        
        
        
        
        
    }
    
    
    public function jovalaszok_leker($kerdessor){
        
     
        
       $kapcs=new kapcsolat;
       $db=$kapcs->ujkapcsolat();
    
       foreach($kerdessor as $value){
           
            $sql='SELECT * FROM allamvizsga_valaszok WHERE id="' . $value . '"';
            
            foreach($db->query($sql) as $row){
            
            $jovalaszok[$row['id']]=$row['valasz'];
            
       }
     
        
    }
     return $jovalaszok;
    
    
    }
    
    
    public function getmagyarazat($value){
        
        $myfile3 = fopen("magyarazat/" . $value . ".txt", "r");
            $msg=fread($myfile3,filesize("magyarazat/" . $value . ".txt"));
            fclose($myfile3);
        
        return $msg;
        
    }
    
     public function kerdessor_konyvel($user_id,$kerdessor){
        
        $kapcs=new kapcsolat;
         $db=$kapcs->ujkapcsolat();
         
       
        $sth = $db->prepare( "SELECT MAX(szet_id) as szet_id FROM `allamvizsga_user_stats`" );
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $szet_id=$result['szet_id']+1;
        
        foreach($kerdessor as $kerdes_id){
            
            
            
         
         
         
         $sql="INSERT INTO allamvizsga_user_stats (user_id,szet_id,kerdes_id,value) VALUES ('" . $user_id . "','" . $szet_id . "','" . $kerdes_id . "','0')";
        
         $sth = $db->prepare($sql);
         $sth->execute();
        
        }
        return $szet_id;
    }
    
    public function veletlen_generalo_tombbol($osszes_kerdes2){
        
        
        $szam=rand(0,count($osszes_kerdes2));
        
        return $szam;
        
        
    }
    
    public function lehetseges_kerdesszam_leker($type){
                    $teszt_type=$type;
                     if($teszt_type=='sima'){
                             $kapcs=new kapcsolat;
                                $db=$kapcs->ujkapcsolat();
    
                    
                               $sql='SELECT * FROM allamvizsga_valaszok WHERE valid=1';
            
                                foreach($db->query($sql) as $row){
            
                                  $kerdessor[]=$row['id'];
                                  
            
                                }   
                                $kerdes_num=count($kerdessor);
            
                    }
        
        
                     if($teszt_type=='csak_rossz'){
                               
            
                                 $kapcs=new kapcsolat;
                                $db=$kapcs->ujkapcsolat();
                                $kerdessor=Array();
                    
                               //$sql='SELECT * FROM allamvizsga_user_stats WHERE user_id="' . $_SESSION['user_id'] . '" AND value=0';
                                $sql="SELECT kerdes_id,max(value) as value from allamvizsga_user_stats WHERE user_id=" . $_SESSION['user_id'] . " group by kerdes_id ";
                                foreach($db->query($sql) as $row){
                                
                                    if($row['value']==0){
                                        $kerdessor[]=$row['kerdes_id'];
                                                         }           
            
                    
        
                                     }
                                     
                               
                                
                                
                                 $kerdes_num=count($kerdessor);
                     }
        
        
        if($teszt_type=='csak_ismeretlen'){
            
            
                        $kapcs=new kapcsolat;
                        $db=$kapcs->ujkapcsolat();
                        $ismert_kerdesek=Array();
                    
                               $sql='SELECT * FROM allamvizsga_valaszok WHERE valid=1';
            
                                foreach($db->query($sql) as $row){
            
                                  $osszes_kerdes[$row['id']]=$row['id'];
            
                                }
                                //print_r($osszes_kerdes);
                                $sql='SELECT * FROM allamvizsga_user_stats WHERE user_id=' . $_SESSION['user_id'] . "";
                              //  print $sql;
                                foreach($db->query($sql) as $row){
            
                                  $ismert_kerdesek[]=$row['kerdes_id'];
            
                                }
                       
                    foreach($ismert_kerdesek as $value){
                                unset($osszes_kerdes[$value]);
                        
                    }
                    foreach($osszes_kerdes as $value){
                        
                        $kerdessor[]=$value;
                    }
                    
                    
                    
                        
                    
            if(count($kerdessor)>=1){                    
                 $kerdes_num=count($kerdessor);
            }else{
                $kerdes_num=0;
            }          
        }
        
        
        
        
         return $kerdes_num;
    }
   
}
////////////////////////////////////////////////////////////////////////////////////////////////
class init{
    
    
    public function basic_init(){
        
        $szamok=new main;
        
        
        print "<div id='init_form'>";
            print "<form method='get' action='test.php'>";
            
                print utf8_decode("Hány kérdés legyen?") ." <input tpye='text' name='kerdesszam' id='kerdesszam' value='25'>";
                print "<br>";
                print "<table>";
                    print "<tr>";
                        print "<td>";
                            print "Sima:";
                        print "</td>";
                        print "<td>";
                            print $szamok->lehetseges_kerdesszam_leker('sima');
                        print "</td>";
                        print "<td>";
                            print "<input name='teszt_tipus' id='teszt_tipus' type='radio' value='sima' checked='checked' />";
                        print "</td>";
                    print "</tr>";
                    print "<tr>";
                        print "<td>";
                            print utf8_decode("Csak az ismeretlen kérdések");
                        print "</td>";
                         print "<td>";
                            print $szamok->lehetseges_kerdesszam_leker('csak_ismeretlen');
                        print "</td>";
                        print "<td>";
                            print"<input name='teszt_tipus' id='teszt_tipus' type='radio' value='csak_ismeretlen'  />";
                    print "</tr>";print "<tr>";
                        print "<td>";
                            print utf8_decode("Csak a rosszul megválaszoltak:");
                        print "</td>";
                         print "<td>";
                            print $szamok->lehetseges_kerdesszam_leker('csak_rossz');
                        print "</td>";
                        print "<td>";
                            print "<input name='teszt_tipus' id='teszt_tipus' type='radio' value='csak_rosszak'  />";
                        print "</td>";
                    print "</tr>";
              print "</table>";
                print "<input type='submit' value='Mehet' id='uj_teszt_submit'>";
            print "</form>";
        
        
        print "</div>";
        
        
        print "<div id='stats'>";
        
            print "<a class='osszes_kerdes_szam'>" . utf8_decode("Összes kérdés: ");
                $this->stat_leker("osszes");
            print "</a><br>";
             print "<a class='osszes_kerdes_szam'>" . utf8_decode(" Összes Valid kérdés: ");
                $this->stat_leker("osszes_valid");
            print "</a><br>";
            print "<a class='osszes_kerdes_szam'>" . utf8_decode(" Vizsgák száma: ");
                $this->stat_leker("szettek_szama");
            print "</a><br>";
            print "<a class='osszes_kerdes_szam'>" . utf8_decode("Összes megkérdezett kérdés: ");
                $this->stat_leker("osszes_user_kerdes");
            print "</a><br>";
            print "<a class='osszes_kerdes_szam'>" . utf8_decode( "Helyes válaszok száma: ");
                $this->stat_leker("helyes_user_kerdese");
            print "</a><br>";
        
        
        print "</div>";
        
        
        
    }
    
    
    public function stat_leker($stat_type){
        
        
        $user_id=$_SESSION['user_id'];
        if($stat_type=='osszes'){
            $sql='SELECT * FROM allamvizsga_valaszok';
            
        }
        if($stat_type=='osszes_valid'){
            $sql='SELECT * FROM allamvizsga_valaszok WHERE valid=1';
            
        }
        if($stat_type=='szettek_szama'){
            $sql='SELECT DISTINCT(szet_id) as szet_id FROM allamvizsga_user_stats WHERE user_id=' . $user_id . ' ORDER BY szet_id DESC';
      // print $sql;
        }
        if($stat_type=='osszes_user_kerdes'){
            $sql='SELECT DISTINCT(kerdes_id) as kerdes_id FROM allamvizsga_user_stats WHERE user_id="' . $user_id . '" ORDER BY kerdes_id DESC';
        
        }
        if($stat_type=='helyes_user_kerdese'){
            $sql='SELECT DISTINCT(kerdes_id) as kerdes_id FROM allamvizsga_user_stats WHERE user_id="' . $user_id . '" AND value=1';
        
        }
        
            $kapcs=new kapcsolat;
         $db=$kapcs->ujkapcsolat();
         
       
        $sth = $db->prepare($sql);
        $sth->execute();
        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
       // print_r($result);
            
            print sizeof($result);
                
        
    }
    public function login(){
        
        
        
        print "<div id='login_form'>";
            print "<form method='post' action='index.php'>";
            
                print "User<input tpye='text' name='user' id='user'>";
                print "Pass<input tpye='password' name='pass' id='pass'>";
                
                print "<input type='submit' value='Mehet'>";
            
            print "</form>";
        
        
        print "</div>";
        
        
        
    }
    
    
    public function login_check($user,$pass){
        
        
           
            $sql='SELECT * FROM allamvizsga_users WHERE nev="' . $user . '" AND pass="' . $pass . '"';
            
            $talalatok=$db->query($sql);
            
            if(count($talalatok)==1){
                
                $_SESSION['user_id']=$talalatok[0]['id'];
                
                
                
            }else{
                
                
            }
            
            
            
       
        
        
        
    }
    
   
    
    
    
    
}

?>
