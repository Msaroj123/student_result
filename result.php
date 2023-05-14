<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Student Result Management System</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/prism/prism.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <link rel="stylesheet" href="css/print.css" media="print" >
        <script src="js/modernizr/modernizr.min.js"></script>
        <style>
            #jgec{
                color: blue;;
                margin-left:100px;
            }
            #top{
                margin-top:40px
            }
            #photo{
                display:flex;
                justify-content:center;
                align-items:center;
                margin-left:300px;
            }
        </style>
    </head>
    <body>
        <div class="main-wrapper">
            <div class="content-wrapper">
                <div class="content-container">

         
                    <!-- /.left-sidebar -->

                    <div class="main-page" >
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-12">
                                    <h2 class="title" align="center" Id='rms'>Result Management System</h2>
                                </div>
                            </div>
                        </div>

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
<?php

$rollid=$_POST['rollid'];
$classid=$_POST['class'];
$_SESSION['rollid']=$rollid;
$_SESSION['classid']=$classid;
$qery = "SELECT   tblstudents.StudentName,tblstudents.RollId,tblstudents.RegDate,tblstudents.StudentId,tblstudents.Status,tblclasses.ClassName,tblclasses.Section from tblstudents join tblclasses on tblclasses.id=tblstudents.ClassId where tblstudents.RollId=:rollid and tblstudents.ClassId=:classid ";
$stmt = $dbh->prepare($qery);
$stmt->bindParam(':rollid',$rollid,PDO::PARAM_STR);
$stmt->bindParam(':classid',$classid,PDO::PARAM_STR);
$stmt->execute();
$resultss=$stmt->fetchAll(PDO::FETCH_OBJ);
$cnt=1;
if($stmt->rowCount() > 0)
{
    foreach($resultss as $row)
    {   
        ?>
        <h3><strong id='jgec'>JALPAIGURI GOVERNMENT ENGINEERING COLLEGE</strong></h3>
        <img src="jgec.jpg" alt="Girl in a jacket" id='photo' width="100" height="100">
        <p id='top'><b>Student Name :</b> <?php echo htmlentities($row->StudentName);?></p>
        <p><b>Student Roll No :</b> <?php echo htmlentities($row->RollId);?>
        <p><b>Student Class:</b> <?php echo htmlentities($row->ClassName);?>(<?php echo htmlentities($row->Section);?>)
        <?php 
    }
?>
                                            </div>
                                            <div class="panel-body p-20">
                                                <table class="table table-hover table-bordered">
                                                <thead>
                                                        <tr>
                                                            <th>Serial No</th>
                                                            <th>Subject</th>    
                                                            <th>Marks</th>
                                                        </tr>
                                               </thead>
                                            <tbody>
<?php                                              
 $query ="select t.StudentName,t.RollId,t.ClassId,t.marks,SubjectId,tblsubjects.SubjectName from (select sts.StudentName,sts.RollId,sts.ClassId,tr.marks,SubjectId from tblstudents as sts join  tblresult as tr on tr.StudentId=sts.StudentId) as t join tblsubjects on tblsubjects.id=t.SubjectId where (t.RollId=:rollid and t.ClassId=:classid)";
$query= $dbh -> prepare($query);
$query->bindParam(':rollid',$rollid,PDO::PARAM_STR);
$query->bindParam(':classid',$classid,PDO::PARAM_STR);
$query-> execute();  
$results = $query -> fetchAll(PDO::FETCH_OBJ);
$cnt=1;
$check="Pass";
if($countrow=$query->rowCount()>0)
{ 

foreach($results as $result)
{
            ?>
                <tr>
                    <th scope="row"><?php echo htmlentities($cnt);?></th>
                    <td id='
                    '><?php echo htmlentities($result->SubjectName);?></td>
                    <td><?php echo htmlentities($totalmarks=$result->marks);?></td>
                </tr>
            <?php 
        $totlcount+=$totalmarks;
        $cnt++;
        if($totalmarks<30)
        {
            $check="Fail";
        }
}
?>
<tr>
                                                <th scope="row" colspan="2">Total Marks</th>
<td><b><?php echo htmlentities($totlcount); ?></b> out of <b><?php echo htmlentities($outof=($cnt-1)*100); ?></b></td>
                                                        </tr>
                                                            <tr>
                                                                <th scope="row" colspan="2">Percntage</th>           
                                                                <td><b><?php echo  htmlentities($totlcount*(100)/$outof); ?> %</b></td>
                                                            </tr>
                                                           <th scope="row" colspan="2">Remarks</th>           
                                                            <td>
                                                                <b> <?php echo  htmlentities($check); ?> </b>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <th scope="row" colspan="2" id='downlode-result'>Download Result</th>           
                                                            <td><b><a ><button onclick ="window.print()" type="button" class="btn btn-success" id="print-btn">Downlode</button> </a> </b></td>
                                                        </tr>
 <?php }
else 
{ 
    ?>     
    <div class="alert alert-warning left-icon-alert" role="alert">
    <strong>Notice!</strong> Your result not declare yet
    <?php 
}
?>
                                        </div>
 <?php 
 } else
 {
     ?>
    <div class="alert alert-danger left-icon-alert" role="alert">
    strong>Oh snap!</strong>
    <?php
    echo htmlentities("Invalid Roll Id");
 }
?>
                                        </div>
                                                	</tbody>
                                                </table>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">               
                                        <div class="col-sm-6">
                                            <a href="index.php"><button type="button" class="btn btn-primary" id='back-to-home'>Back to Home</button></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!-- ========== COMMON JS FILES ========== -->
        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>

        <!-- ========== PAGE JS FILES ========== -->
        <script src="js/prism/prism.js"></script>

        <!-- ========== THEME JS ========== -->
        <script src="js/main.js"></script>
        <script>
            $(function($) {

            });
        </script>

        <!-- ========== ADD custom.js FILE BELOW WITH YOUR CHANGES ========== -->

    </body>
</html>
