<?php include 'custom_incontact_functions.php';?>

<table class="table">
                                        <thead>
                                            <tr>
                                            <th>Agent Name</th>
											<th>Cur. Skill Name</th>
											<th>Cur. State</th>
                                        </tr>
                                    </thead>
                                    <tbody>
										<?php 
										$b = get_LiveAgents();
										
										foreach($b as $person){ 

										$a = $person->CurrentState; 
										if($a == "Available") {$color = "green";} 
										else if($a == "OutboundContact"){$color = "yellow";}
										else if($a == "InboundContact"){$color = "purple";}										   
										else if($a == "ACW"){$color = "orange";}
										else {$color = "red";}

										?>
										
										<?php if($person->TeamName == "FAST"){ ?>
										<tr>
										<td><?php echo $person->FirstName . " " . $person->LastName;  ?></td>
										<?php echo "<td class='odd gradeX'>" . (trim($person->CurrentSkillName, "FAST:Farmers")) . "</td>"; ?>
										<?php echo "<td class='odd gradeX'><img src='incontact/incontact_images/icon_state_$color.png'/> &nbsp;&nbsp;" . $a . "</td>"; ?>
                                        </tr>
                        
										<?php } else{}?>
										

										<?php } 

										?></tbody>
                                    </table>
									

                                