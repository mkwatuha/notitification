<?php
restrictaccess();
function restrictaccess(){
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "../../index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($QUERY_STRING) && strlen($QUERY_STRING) > 0) 
  $MM_referrer .= "?" . $QUERY_STRING;
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
}
require_once('../../Connections/cf4_HH.php');
?><?php
require('fpdf.php'); 
include('../../template/functions/menuLinks.php');
$employeeid=$_GET['t'];
 $printpayslip='';
 $printpnine=''; 

if(isset($_POST['printpayslip'])){
       $printpayslip='printpaysli'  ;
}
if(isset($_POST['printpnine'])){
      $printpnine='printpnine'  ;
}
$printpayslip='kkk';
if($printpayslip)  {
class PDF extends FPDF
{
//Page header
function Header()
{
 
$this->SetFont('Arial','',9);

$employees_name='';
$employees_National='';
$department='';
$employeePF='';
  
  $pid=base64_decode($_GET['rd']);
  $pd=base64_decode($_GET['pd']);
  
   $pid=$_GET['rd'];
  $pd=$_GET['pd'];
  
  $person_id=$pid;
  $payperiod_id=$pd;
  $std=getEmpPayPeriodStandard($person_id,$payperiod_id);
  $pdeductions=getEmpPayPeriodDeductions($person_id,$payperiod_id);
  $pbenefits=getEmpPayPeriodBenefits($person_id,$payperiod_id);

  $employeedid=$pid;
   $personal=fillPrimaryData('admin_person',$pid);
  $prefferedContacts=getPrefferedContact('admin_person',$pid);
  $postal_address=$prefferedContacts['postal_address'];
  
  $employees_name=$personal['person_fullname'];

   $employeePF=getEmployeeNumber($pid);
   $nssf_number=getNssFNumber($pid);
   $nhif_number=getNhifFNumber($pid);
   $pin_number=$personal['pin'];
  $deptArr=getDepartment($pid);
  $department=$deptArr['dept_name'];
  
  //basic pay
  $paygradeArr=getPaygradeLevel($pid);
  $paygradeLevel=$paygradeArr['paygrade'];
  $basicPay=$paygradeArr['salary_basic'];
  $benefitbygrade=$paygradeArr['paygradecompared'];
  $deductionbygrade=$paygradeArr['paygradecompareddeduction'];
  
  
  //hrpayroll_deductionbygrade
  
  //company info
  $campanyDetail=fillPrimaryData('admin_company',1);
  $companyname=$campanyDetail['company_name'];
  
  //banking
  $bacountde=getBankInfo($pid);
  $bacount=$bacountde['bankaccount_name'];
  $bbranch=$bacountde['branch'];
  $bank=$bacountde['bank_name'];
  
  
//$taxablepay=96180;
$relief=1162;
//$TotalTax=calculate_tax($taxablepay);
$monthdate=date('F-Y');
//$TotalTax=calculatepayeTax($taxablepay);

  
  
  //$employees_name='Kwatuha Alfayo Mulimani Mmbwanga';
  $employee=1255500.00;
  $this->SetFont('Arial','',9);
  
  //$this->Cell(60,5,'********************************************',0,1,'L');
   $this->SetFont('Times','B',18);
  $this->Cell(60,5,$companyname,0,1,'L');
   $this->SetFont('Times','',9);
   $this->Ln(2);
  
  $this->Cell(85,5,'********************************************',0,1,'L');
  
  $this->Cell(60,5,'PF No: ',0,0,'L');
  $this->SetFont('Arial','B',9);
  $this->Cell(10,5,$employeePF,0,1,'R');
  $this->SetFont('Arial','',9);
  
  
  $this->Cell(60,5,'Name: ',0,0,'L');
  $this->SetFont('Arial','B',9);
  $this->Cell(10,5,$employees_name,0,1,'R');
  $this->SetFont('Arial','',9);
  
  $this->Cell(60,5,'Dept: ',0,0,'L');
  $this->SetFont('Arial','B',9);
  $this->Cell(10,5,$department,0,1,'R');
  $this->SetFont('Arial','',9);
  
  
  $this->Cell(60,5,'Grades: ',0,0,'L');
 $this->SetFont('Arial','B',9);
  $this->Cell(10,5,$paygradeLevel,0,1,'R');
  $this->SetFont('Arial','',9);
  
  $this->Cell(60,5,'National: ',0,0,'L');
  $this->SetFont('Arial','B',9);
  $this->Cell(10,5,$employees_National,0,1,'R');
  $this->SetFont('Arial','',9);
  
  $this->Cell(60,5,'Month: ',0,0,'L');
  
  $this->SetFont('Arial','B',9);
  $this->Cell(10,5,$monthdate,0,1,'R');
  $this->SetFont('Arial','',9);
  
  $this->SetFont('Arial','B',10);
  $this->Ln(1);
  $this->Cell(60,5,'Earnings :-',0,1,'L');
  $this->SetFont('Arial','',9);
 $this->Cell(85,5,'********************************************',0,1,'L');
   $this->Cell(60,5,'Basic Salary',0,0,'L');
  $this->Cell(10,5,number_format($std['basic_pay'],2),0,1,'R');
  
  /*
  $this->Cell(60,5,'House Allowance: '.$employee,0,0,'L');
  $this->Cell(10,5,$employee,0,1,'R');
  $this->Cell(60,5,'Commuter allowance',0,0,'L');
  $this->Cell(10,5,$employee,0,1,'R');
  $this->Cell(60,5,'Risk Allowance',0,0,'L');
  $this->Cell(10,5,$employee,0,1,'R');*/
  
  $totalbenefits='';
//$benefits=getAllowance($employeedid);


if($pbenefits){
	  $benefitARR='';
	  foreach($pbenefits as $benefititem){
	  $benefitARR=explode('|', $benefititem);
	  
	  $this->Cell(60,5,$benefitARR[0],0,0,'L');
	  $this->Cell(10,5,number_format($benefitARR[1],2),0,1,'R');
	   $totalbenefits=$totalbenefits+$benefitARR[1];
	  }
  }
  $this->Cell(85,5,'********************************************',0,1,'L');
  
  
 $this->SetFont('Arial','B',10);
 $this->Cell(60,5,'Gross Pay ',0,0,'L');
  $this->Cell(10,5, number_format($std['gross_pay'],2),0,1,'R');
  $this->SetFont('Arial','',9);
  
  $this->SetFont('Arial','B',10);
   $this->Ln(1);
  $this->Cell(60,5,'Taxations :-',0,1,'L');
  $this->SetFont('Arial','',9);
  $this->Cell(85,5,'********************************************',0,1,'L');
  /*$this->Cell(60,5,'Contribution Benefit',0,0,'L');
  $this->Cell(10,5,'200.00',0,1,'R');*/

  $grosspay=$totalbenefits+$basicPay;
  $taxablepay=$grosspay-200;
  $TotalTax=PayeCal($taxablepay);
  $paye=$TotalTax-$relief;
  $taxablepay=$std['gross_pay']-$std['nssf'];
  $this->Cell(60,5,'Chargeable Pay',0,0,'L');
  $this->Cell(10,5,number_format($taxablepay,2),0,1,'R');
  $this->Cell(60,5,'Tax Charged Month',0,0,'L');
  $this->Cell(10,5,number_format($TotalTax,2),0,1,'R');
  $this->Cell(60,5,'Monthly Personal Relief',0,0,'L');
  $this->Cell(10,5,number_format($relief,2),0,1,'R');

   $this->Ln(1);
    $this->SetFont('Arial','B',9);
  $this->Cell(60,5,'Deductions :-',0,1,'L');
  $this->SetFont('Arial','',9);
 $this->Cell(85,5,'********************************************',0,1,'L');
   $this->Cell(60,5,'PAYE',0,0,'L');
  $this->Cell(10,5,number_format($std['paye'],2),0,1,'R');
  $this->Cell(60,5,'NHIF',0,0,'L');
  $this->Cell(10,5,number_format($std['nhif'],2),0,1,'R');
   $this->Cell(60,5,'NSSF',0,0,'L');
  $this->Cell(10,5,number_format($std['nssf'],2),0,1,'R');
  
  /*$this->Cell(60,5,'NSSF '.$employee,0,0,'L');
  $this->Cell(10,5,$employee,0,1,'R');
  $this->Cell(60,5,'NHIF',0,0,'L');
  $this->Cell(10,5,$employee,0,1,'R');*/
   $totaldeductions='';
  //$pdeductions= getDeductionsByGrade($deductionbygrade);//getDeductions($employgrade);
  if($pdeductions){
	  $deductionsARR='';
	  foreach($pdeductions as $item){
	  $deductionsARR=explode('|', $item);
	  $this->Cell(60,5,$deductionsARR[0],0,0,'L');
	  $this->Cell(10,5,number_format($deductionsARR[1],2),0,1,'R');
	  $totaldeductions=$totaldeductions+$deductionsARR[1];
	  }
  }
  
  
  $this->Ln(1);
    $this->SetFont('Arial','B',9);
  $this->Cell(60,5,'Loans Deductions :-',0,1,'L');
  $this->SetFont('Arial','',9);
 $this->Cell(85,5,'********************************************',0,1,'L');
 $installmtnDE='';
 
  $loandeductions= getEmployeeLoaninfo($employeedid);
  if($loandeductions){
	  $deductionsARR='';
	  foreach($loandeductions as $item){
	  $loandeductionsARR=explode('||', $item);
	  $this->Cell(60,5,$loandeductionsARR[0],0,0,'L');
	  $ratedintre=$loandeductionsARR[4]/100+1;
	  
	  $installmtn=($loandeductionsARR[2]*$ratedintre)/$loandeductionsARR[3];
	  $this->Cell(10,5,number_format($installmtn,2),0,1,'R');
	   //insertRepaymentDetails($employeedid,$loandeductionsARR[5],$datetaken,$installmtn,$loandeductionsARR[2]*$ratedintre);
	   //$employloanArray[$count]=$loan_name.'||'.$start_date.'||'.$loan_amount.'||'.$repayment_period.'||'.$interest_rate||loan_id;
		
	  $installmtnDE=$installmtnDE+$installmtn;
	  }
  }
  
  
  
 $this->SetFont('Arial','B',9);
 $this->Ln(1); 
 $this->Cell(60,5,'TOTAL DEDUCTIONS',0,0,'l');
 $this->Cell(10,5, number_format($totaldeductions+$paye,2),0,1,'R');
 $this->SetFont('Arial','',9);
 $netpays=$taxablepay-($paye+$totaldeductions+$installmtnDE);
 $this->SetFont('Arial','B',9);
 $this->Ln(1); 
 $this->Cell(60,5,'NET PAY',0,0,'l');
 $this->Cell(10,5,number_format($netpays,2),0,1,'R');
 $this->SetFont('Arial','',9);
 
  $this->SetFont('Arial','B',9);
 $this->Ln(1); 
 $this->Cell(60,5,'Payment Details',0,1,'l');
  $this->SetFont('Arial','',8);
 $this->Cell(60,5,'Bank:'.$bank,0,1,'L');
 $this->Cell(60,5,'Branch:'.$bbranch,0,1,'L');
  $this->Cell(60,5,'A/C:'.$bacount,0,1,'L');
 $this->SetFont('Arial','',9);
 
 $this->SetFont('Arial','B',9);
 $this->Ln(1); 
 $this->Cell(60,5,'Messages:-',0,0,'l');
 $this->Cell(10,5,'',0,1,'R');
 $this->SetFont('Arial','',9);
  $this->Cell(85,5,'********************************************',0,1,'L');

    $this->Ln(5);
}
//Page footer
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-25);
    //Arial italic 8
    $this->SetFont('Courier','',10);
    //Page number
	
    $this->Cell(190,6,$companyname,0,1,'C');
	 $this->SetY(-18);
	 $this->SetFont('Courier','I',8);
  $this->Cell(190,6,'Page '.$this->PageNo().' of {nb}',0,1,'C');
}



}
$pdf=new PDF('P');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->SetFont('Arial','',8);
$pdf->Output();


}




if($printpnine)    {
class pdf extends FPDF
{
function Header(){
$companyArrInfo=getCompanyInfo();
$companyArr=explode('||', $companyArrInfo[0]);
$this->setFont('Arial','B',10);
$year='/2011';
$this->setY(10);
$this->cell(0,4,$companyArr[0],0,1,'C');
//$this->Image('images/moi.jpg',175,30,27,23);
//$this->cell(0,4,'P.O Box 817 Nairobi',0,1,'C');
$this->Cell(0,4,'P.O. Box  '.$companyArr[4].' - '.$companyArr[5].'  '.$companyArr[6],0,1,'C');
//$this->Cell(80);
//$this->Cell(40,5,' Tel: '.$companyArr[7].' Fax: '.$companyArr[8],0,0,'C');
//$this->Cell(80);
/*$this->Cell(20,5,' Mobile: '.$companyArr[9],0,0,'C');
$this->Cell(30,5,'Email: '.$companyArr[10],0,0,'C');
$this->Cell(30,5,'Website: '.$companyArr[11],0,1,'C');*/


$this->cell(0,4,'Tax Deduction Card for the Year 2011',0,0,'C');

$this->Ln(10);


		
	}
	
function Footer(){
	
	//Position at 2.5 cm from bottom
    $this->SetY(-50);
    //Arial italic 8
    $this->SetFont('Courier','',10);
   $companyArrInfo=getCompanyInfo();
$companyArr=explode('||', $companyArrInfo[0]);
	
	$this->setY(-20);
	//set font for the footer
	$this->setfont('Courier','',8);
	$this->cell(0,5,$companyArr[0],0,1,'C');
	$this->Ln(4);
		
	}
function load_data(){

$employees_name='';
$employees_National='';
$department='';
$employeePF='';
$employeedid=$_POST["employee_name"]; 
 $employeeArrayItem= getEmployeeInfo($employeedid);
  if($employeeArrayItem){
	  $employeeArrayItemsARR='';
	  foreach($employeeArrayItem as $empitem){
	  $employeeArrayItemsARR=explode('||', $empitem);
	  
	  $employees_name=$employeeArrayItemsARR[0];
	  $employees_National=$employeeArrayItemsARR[1];
	  $employeePF=$employeeArrayItemsARR[2];
	  $nssf_number=$employeeArrayItemsARR[3];
	  $pin_number=$employeeArrayItemsARR[4];
	  $nhif_number=$employeeArrayItemsARR[5];
	  }
  }
  
  $deptArr=getDepartment($employeedid);
  if($deptArr){
	  $deptItemsARR='';
	  foreach($deptArr as $deptitem){
	  $deptItemsARR=explode('||', $deptitem);
	  
	  $department=$deptItemsARR[0];
	  
	  }
  }
  $paygradeLevel='';
  $basicPay='';
  $paygradeArr=getPaygradeLevel($employeedid);
  if($paygradeArr){
	  $paygradeArrARR='';
	  foreach($paygradeArr as $plitem){
	  $paygradeArrARR=explode('||', $plitem);
	  
	  $paygradeLevel=$paygradeArrARR[0].'  '.$paygradeArrARR[1];
	  $basicPay=$paygradeArrARR[2];
	  
	  }
  }
  
  $employeedid=$_POST["employee_name"]; 

$relief=1162;
$TotalTax=calculate_tax($taxablepay);
$monthdate=date('F-Y');


$xplodname=explode(" ",$employees_name);
$arrsize=sizeof($xplodname);
if($arrsize==3){
$fname=$xplodname[0];
$lname=$xplodname[1];
$mname=$xplodname[2];
}
else{
$fname=$xplodname[0];
$lname=$xplodname[1];
$mname="";
}

 $nssf=200;
 $totalbenefits='';
$benefits=getAllowance($employeedid);
if($benefits){
	  $benefitARR='';
	  foreach($benefits as $benefititem){
	  $benefitARR=explode('||', $benefititem);
	   $totalbenefits=$totalbenefits+$benefitARR[0];
	  }
  }	

//$this->setY(20);
$this->setFont('Courier','B',10);
/*
$this->cell(35,5,'Employee No:','BTL',0,'R');
$this->cell(35,5,$employeedid,'BT',0,'L');
$this->cell(35,5,'Last Name:','BT',0,'R');
$this->cell(35,5,$lname,'BT',0,'L');
$this->cell(35,5,'First Name:','BT',0,'R');
$this->cell(35,5,$fname,'BT',0,'L');
$this->cell(35,5,'Middle Name:','BT',0,'R');
$this->cell(36,5,$mname,'BTR',1,'L');
*/
$othernames=$fname." ".$mname;

$this->SetFont('Courier','',8);
$companyArrInfo=getCompanyInfo();
$companyArr=explode('||', $companyArrInfo[0]);
$this->cell(180,4,'Employer\'s Name: '.$companyArr[0],0,0,'L');
$this->cell(40,4,'Employer\'s PIN:',0,0,'R');
$this->cell(40,4,$companyArr[1],0,1,'L');

$this->cell(180,4,'Employees Main Name: '.ucwords($lname),0,1,'L');
//$this->Ln(2);

$this->cell(180,4,'Employees other Name: '.ucwords($othernames),0,0,'L');
$this->cell(40,4,'Employee\'s PIN:',0,0,'R');
$this->cell(40,4,$pin_number,0,1,'L');
$this->Ln(1);
$this->SetFont('Courier','',8);
//Drawing rectangles:row one
$this->rect(10,41,16,18);
$this->rect(26,41,17,18);
$this->rect(43,41,17,18);
$this->rect(60,41,20,18);
$this->rect(80,41,18,18);
$this->rect(98,41,44,18);
$this->rect(142,41,17,18);
$this->rect(159,41,26,18);
$this->rect(185,41,23,18);
$this->rect(208,41,25,18);
$this->rect(233,41,24,18);
$this->rect(257,41,17,18);
$this->rect(274,41,17,18);

//Drawing rectangles:row two

$this->rect(10,59,16,20);
$this->rect(26,59,17,20);
$this->rect(43,59,17,20);
$this->rect(60,59,20,20);
$this->rect(80,59,18,20);
$this->rect(98,59,44,8);
$this->rect(98,67,15,12);
$this->rect(113,67,16,12);
$this->rect(129,67,13,12);
$this->rect(142,59,17,20);
$this->rect(159,59,26,20);
$this->rect(185,59,23,8);
$this->rect(185,67,23,12);
$this->rect(208,59,25,8);
$this->rect(208,67,25,12);
$this->rect(233,59,24,8);
$this->rect(233,67,41,12);
$this->rect(257,59,17,8);
$this->rect(274,59,17,8);
$this->rect(274,67,17,12);

//printing the last portion of the header
$this->cell(16,4,'MONTH',0,0,'L');
$this->cell(17,4,'Basic',0,0,'L');
$this->cell(17,4,'Benefits',0,0,'L');
$this->cell(20,4,'Value',0,0,'L');
$this->cell(18,4,'Total',0,0,'L');
$this->cell(44,4,'Defined Contribution',0,0,'L');
$this->cell(17,4,'Savings',0,0,'L');
$this->cell(26,4,'Retirement',0,0,'L');
$this->cell(23,4,'Chargeable',0,0,'L');
$this->cell(25,4,'Tax',0,0,'L');
$this->cell(24,4,'Monthly',0,0,'L');
$this->cell(17,4,'Insurance',0,0,'L');
$this->cell(17,4,'P.A.Y.E',0,1,'L');

$this->cell(16,4,'',0,0,'L');
$this->cell(17,4,'Pay',0,0,'L');
$this->cell(17,4,'Non-Cash',0,0,'L');
$this->cell(20,4,'of Quarters',0,0,'L');
$this->cell(18,4,'Gross Pay',0,0,'L');
$this->cell(44,4,'Retirement Scheme',0,0,'L');
$this->cell(17,4,'Plan',0,0,'L');
$this->cell(26,4,'Contribution &',0,0,'L');
$this->cell(23,4,'Pay',0,0,'L');
$this->cell(25,4,'Charged',0,0,'L');
$this->cell(24,4,'Relief',0,0,'L');
$this->cell(17,4,'Relief',0,0,'L');
$this->cell(17,4,'Tax',0,1,'L');

$this->cell(16,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(20,4,'',0,0,'L');
$this->cell(18,4,'A+B+C',0,0,'L');
$this->cell(44,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(26,4,'Savings plan',0,0,'L');
$this->cell(23,4,'',0,0,'L');
$this->cell(25,4,'',0,0,'L');
$this->cell(24,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,1,'L');
//$this->cell(1);

$this->cell(16,6,'',0,0,'L');
$this->cell(17,6,'Kshs.',0,0,'L');
$this->cell(17,6,'Kshs.',0,0,'L');
$this->cell(20,6,'Kshs.',0,0,'L');
$this->cell(18,6,'Kshs.',0,0,'L');
$this->cell(44,6,'Kshs.',0,0,'L');
$this->cell(17,6,'Kshs.',0,0,'L');
$this->cell(26,6,'Kshs.',0,0,'L');
$this->cell(23,6,'Kshs.',0,0,'L');
$this->cell(25,6,'Kshs.',0,0,'L');
$this->cell(24,6,'Kshs.',0,0,'L');
$this->cell(17,6,'Kshs.',0,0,'L');
$this->cell(17,6,'Kshs.',0,1,'L');


$this->cell(16,4,'',0,0,'L');
$this->cell(17,4,'A',0,0,'L');
$this->cell(17,4,'B',0,0,'L');
$this->cell(20,4,'C',0,0,'L');
$this->cell(18,4,'D',0,0,'L');
$this->cell(44,4,'E',0,0,'C');
$this->cell(17,4,'F',0,0,'L');
$this->cell(26,4,'G',0,0,'L');
$this->cell(23,4,'H',0,0,'L');
$this->cell(25,4,'J',0,0,'L');
$this->cell(24,4,'K',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'L',0,1,'L');

$this->cell(16,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(20,4,'',0,0,'L');
$this->cell(18,4,'',0,0,'L');
$this->cell(44,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(26,4,'',0,0,'L');
$this->cell(23,4,'',0,0,'L');
$this->cell(25,4,'',0,0,'L');
$this->cell(24,4,'1162',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,1,'L');


$this->cell(16,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(20,4,'',0,0,'L');
$this->cell(18,4,'',0,0,'L');
$this->cell(15,4,'E1',0,0,'L');
$this->cell(16,4,'E2',0,0,'L');
$this->cell(13,4,'E3',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(26,4,'',0,0,'L');
$this->cell(23,4,'',0,0,'L');
$this->cell(25,4,'',0,0,'L');
$this->cell(24,4,'Total',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,1,'L');

$this->cell(16,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(20,4,'',0,0,'L');
$this->cell(18,4,'',0,0,'L');
$this->cell(15,4,'30%',0,0,'L');
$this->cell(16,4,'Actual',0,0,'L');
$this->cell(13,4,'Legal',0,0,'L');
$this->cell(17,4,'Amount',0,0,'L');
$this->cell(26,4,'The lowest of',0,0,'L');
$this->cell(23,4,'',0,0,'L');
$this->cell(25,4,'',0,0,'L');
$this->cell(24,4,'Kshs. 1162',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,1,'L');

$this->cell(16,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(20,4,'',0,0,'L');
$this->cell(18,4,'',0,0,'L');
$this->cell(15,4,'of A',0,0,'L');
$this->cell(16,4,'Cont',0,0,'L');
$this->cell(13,4,'Limit',0,0,'L');
$this->cell(17,4,'Deposited',0,0,'L');
$this->cell(26,4,'E added to F',0,0,'L');
$this->cell(23,4,'',0,0,'L');
$this->cell(25,4,'',0,0,'L');
$this->cell(24,4,'',0,0,'L');
$this->cell(17,4,'',0,0,'L');
$this->cell(17,4,'',0,1,'L');


$fill=true;
$totbasic_sal=0;
$totben=0;
$totgross=0;
$tote1=0;
$totE2=0;
$totE3=0;
$totmonthly_relief=0;
$totsavingPlan=0;
$totret_cont_sav_plan=0;
$totchargeable_pay=0;
$tottaxpaid=0;
$totpaye=0;

for($i=0;$i<12;$i++){
$basic_sal=$basicPay;

$ben=$totalbenefits;
$quarter_value=0;
$gross=$basic_sal +$ben + $quarter_value;
$e1=(30/100 * $basic_sal);
$E2=$nssf;//[$i];
$E3=20000;//$e3[$i];
$monthly_relief=$relief;
$savingPlan=$nssf;
$ret_cont_sav_plan=(min($e1,$E2,$E3) + $savingPlan);
$chargeable_pay=($gross - $ret_cont_sav_plan);
$taxpaid=PayeCal($chargeable_pay);;
$paye=($taxpaid-$monthly_relief);

//############### Get the sums ####################
$totbasic_sal+=$basic_sal;
$totben+=$ben;
$totgross+=$gross;
$tote1+=$e1;
$totE2+=$E2;
$totE3+=$E3;
$totmonthly_relief+=$monthly_relief;
$totsavingPlan+=$savingPlan;
$totret_cont_sav_plan+=$ret_cont_sav_plan;
$totchargeable_pay+=$chargeable_pay;
$tottaxpaid+=$taxpaid;
$totpaye+=$paye;

///////////////////////////////////////////////////
$this->SetFillColor(200, 255, 220);
$this->SetTextColor(10);
$this->SetFont( 'Courier', 'B', 8 );


$this->cell(16,4,'',1,0,'L',$fill);
$this->cell(17,4,number_format($basic_sal),1,0,'R',$fill);
$this->cell(17,4,number_format($ben),1,0,'R',$fill);
$this->cell(20,4,number_format($quarter_value),1,0,'R',$fill);
$this->cell(18,4,number_format($gross),1,0,'R',$fill);
$this->cell(15,4,number_format($e1),1,0,'R',$fill);
$this->cell(16,4,number_format($E2),1,0,'R',$fill);
$this->cell(13,4,number_format($E3),1,0,'R',$fill);
$this->cell(17,4,number_format($savingPlan),1,0,'R',$fill);
$this->cell(26,4,number_format($ret_cont_sav_plan),1,0,'R',$fill);
$this->cell(23,4,number_format($chargeable_pay),1,0,'R',$fill);
$this->cell(25,4,number_format($taxpaid),1,0,'R',$fill);
$this->cell(41,4,number_format($monthly_relief),1,0,'R',$fill);

$this->cell(17,4,number_format($paye),1,1,'R',$fill);

 $fill=!$fill;

}//end for
//############################## Totals #############################################
$totbasic_sal+=$basic_sal;
$totben+=$ben;
$totgross+=$gross;
$tote1+=$e1;
$totE2+=$E2;
$totE3+=$E3;
$totmonthly_relief+=$monthly_relief;
$totsavingPlan+=$savingPlan;
$totret_cont_sav_plan+=$ret_cont_sav_plan;
$totchargeable_pay+=$chargeable_pay;
$tottaxpaid+=$taxpaid;
$totpaye+=$paye;



$this->cell(16,4,'TOTALS',1,0,'L',$fill);
$this->cell(17,4,number_format($totbasic_sal),1,0,'R',$fill);
$this->cell(17,4,number_format($totben),1,0,'R',$fill);
$this->cell(20,4,number_format($quarter_value),1,0,'R',$fill);
$this->cell(18,4,number_format($totgross),1,0,'R',$fill);
$this->cell(15,4,number_format($tote1),1,0,'R',$fill);
$this->cell(16,4,number_format($totE2),1,0,'R',$fill);
$this->cell(13,4,number_format($totE3),1,0,'R',$fill);
$this->cell(17,4,number_format($totsavingPlan),1,0,'R',$fill);
$this->cell(26,4,number_format($totret_cont_sav_plan),1,0,'R',$fill);
$this->cell(23,4,number_format($totchargeable_pay),1,0,'R',$fill);
$this->cell(25,4,number_format($tottaxpaid),1,0,'R',$fill);
$this->cell(41,4,number_format($totmonthly_relief),1,0,'R',$fill);

$this->cell(17,4,number_format($totpaye),1,1,'R',$fill);
$this->Ln(2);

//////#################### Last writings #######################################
$this->SetFont('Times','',8);
$this->cell(193,4,'TOTAL TAX (COL. L) Kshs.',0,0,'R');
$this->cell(20,4,number_format($totpaye),'B',1,'L');
$this->SetFont('Times','',8);
$this->cell(0,3,'To be completed by Employer at end of year',0,1,'L');
$this->SetFont('Times','',8);
$this->cell(56,4,'TOTAL CHARGEABLE PAY (COL. H) Kshs.',0,0,'L');
$this->cell(20,4,number_format($totchargeable_pay),'B',0,'L');
$this->cell(130,4,'NAME OF APPROVED INSTITUTION:',0,0,'R');
$this->cell(50,4,$companyArr[0],'B',1,'L');
$this->Ln(2);
$this->cell(219,4,'REGISTRATION NUMBER OF APPROVED INST',0,0,'R');
$this->cell(30,4,$companyArr[12],'B',1,'L');

$this->Ln(2);
$this->cell(192,4,'DATE OF REGISTRATION',0,0,'R');
$this->cell(30,4,$companyArr[3],'B',1,'L');
//####################################################################################

//$this->Ln(500);

	
	
	
}
}
$pdf=new PDF('L');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Courier','',8);
$pdf->load_data();
$pdf->SetFont('Arial','',8);
$pdf->Output();
}
?>