<?php
include 'dbc.php';

$xml_str = file_get_contents("upload/{$_FILES['file']['name']}");
$xml = new SimpleXMLElement($xml_str);
$item = $xml->Member;

foreach($item as $member){

$membersid = $member->Contact->MemID;
$firstname = $member->Contact->FirstName;
$midinit = $member->Contact->MidInit;
$lastname = $member->Contact->LastName;
$birthdate = $member->Contact->BirthDate;
$gender = $member->Contact->Gender;
$address = $member->Contact->Address;
$address2 = $member->Contact->Address2;
$city = $member->Contact->City;
$state = $member->Contact->State;
$zip = $member->Contact->Zip;
$homephone = $member->Contact->HomePhone;
$email = $member->Contact->Email;

$lastfour = $member->Plan->LastFour;
$ccexpiration = $member->Plan->CCExpiration;
$dependants = $member->Plan->Deps;
$deprelation = $member->Plan->DepRelation;
$effectivedate = $member->Plan->EffectiveDate;
$dentaloffice = $member->Plan->DentalOffice;
$enrollplansid = $member->Plan->EnrollPlansID;
$planfee = $member->Plan->PlanFee;
$referedby = $member->Plan->ReferedBy;
$other = $member->Plan->Other;
$comments= $member->Plan->Comments;

$insertrecs = mysql_query("
INSERT INTO xml_post
(
MembersID,
FirstName,
MidInit,
LastName,
BirthDate,
Gender,
Address,
Address2,
City,
State,
Zip,
HomePhone,
Email,
LastFour,
CCExpiration,
EffectiveDate,
DentalOffice,
Dependents,
EnrollPlansID,
PaymentPeriod,
OrderPlanFee,
OrderTotal,
ReferedBy,
Other,
Comments,
post_timestamp
)
VALUES
(
'$membersid',
'$firstname',
'$midinit',
'$lastname',
'$birthdate',
'$gender',
'$address',
'$address2',
'$city',
'$state',
'$zip',
'$homephone',
'$email',
'$lastfour',
'$ccexpiration',
'$effectivedate',
'$dentaloffice',
'$dependents',
'$enrollplansid',
'$paymentperiod',
'$orderplanfee',
'$ordertotal',
'$referedby',
'$other',
'$comments',
NOW()
)
")or die(mysql_error()) ;

}

?>