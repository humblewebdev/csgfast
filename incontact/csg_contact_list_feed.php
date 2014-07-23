<?php include 'custom_incontact_functions.php';?>

<table class="table">
                                         <thead>
                                            <tr>
											<th>Current Skill Name</th>
											<th>Time</th>
											<th>Cur. State</th>
                                        </tr>
                                    </thead>
                                    <tbody>
									
										<?php 
			                            $b = get_LiveContacts();
										
										foreach($b as $person){ 

										$fullskill = $person->SkillName;
										$skillname = substr($fullskill, 0, 4);
										$start_date_exp = explode("T", $person->StartDate);
										//0000-00-00 Start Date Format Below
										$sd1 = $start_date_exp[0];
										//00:00:00 Start Time Format Below
										$sd2exp = explode(".", $start_date_exp[1]);
										$sd2 = $sd2exp[0];
										//0000-00-00 00:00:00 End Date Time Format Below
										$ed = date('Y-m-d H:i:s');
  							            $start_date = new DateTime("$sd1 $sd2");
                                        $end_date = new DateTime("$ed");
                                        $dd = date_diff($start_date, $end_date);
										$longdif = $dd->h . ":" . ((($dd->i)+60)%60) . ":" . ((($dd->s)+60)%60);
                                        //Re-Explodes Time to seperate and save as array variables
										$fdexp = explode(":", $longdif);
										$finaldif = $fdexp[0] . "h " . $fdexp[1] . "m " . $fdexp[2] . "s ";
										
										//Choose State Color By Value
										$a = $person->State; 
										if($a == "Active" || $a == "Conference") {$color = "green";} 
										else if($a == "Transfer"){$color = "blue";}
										else if($a == "Hold"){$color = "gray";}										   
										else if($a == "Routing"){$color = "orange";}										
										else if($a == "Prequeue"){$color = "yellow";}
										else {$color = "red";}
										
										if($skillname == "FAST"){ ?>
										<tr>
										<?php echo "<td class='odd gradeX'><img src='incontact/incontact_images/mediatype_4.png'/> &nbsp;&nbsp;" . (trim($person->SkillName, "FAST:")). "</td>"; ?>
										<td><?php echo $finaldif;  ?></td>
										<?php echo "<td class='odd gradeX'><img src='incontact/incontact_images/icon_state_$color.png'/> &nbsp;&nbsp;" . $person->State. "</td>"; ?>
							            </tr>
										<?php } ?>
      
										
                        

										<?php } //var_dump($d);
										?></tbody>
</table>
                                   
                                