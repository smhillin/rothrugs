<style>
	table .listPerson
{
    padding: 0;
    font-size: 12pt;
    background: #FFFFFF;
    text-align: center;
    vertical-align: middle;
	padding-right: 10px;
}

.listPerson td
{
   padding: 5px;
vertical-align: middle;
text-align: center;
 width:        60px;
}

.listPerson tr  td{
	border-bottom: solid 1px #ff0087;
}


.listPerson td .picture{
	width:        50px;
}
.dateTime{padding: 10px;
border: 1px solid #CCC;}
</style>
<div style=" width:190mm;position: relative;display: table;margin: auto;height:270mm;margin-top:5mm;">
		<div style="  float: left;background: #fff;position: relative; width: 190mm;
display: table;border-radius: 20px;border:5px solid #ff0087; height: 100%;position:relative;">
			
			
			<table style="margin-left: 15px;text-align: center; vertical-align: middle;width:  98%;">
				<tr>
					<?php $style = ImageHelper::getStyleSize100($logo, 200, 100); ?>
					<td  style=" vertical-align: middle; text-align: center; width: 700px;height: 200px">
					
						<img <?php echo $style ?> src="<?php echo $logo ?>"  />

					</td>
				</tr>

			</table>
			<div style="padding-left: 200px;">
				<table ><tr>
						<td style="padding: 10px">From</td>
						<td style="border:1px solid #ccc;padding: 10px"><?php echo $fr; ?></td>
						<td style="padding: 10px">To</td><td style="border:1px solid #ccc;padding: 10px"> <?php echo  $to ?></td>
					</tr>
				</table>
			</div>
				<?php if(isset($department)):?>
			<div>
				<span style='margin-left: 30px;color:#ed3380;font-size:24px;font-weight:bold;'><?php echo $department ?></span>
			</div>
			<?php endif;?>
			<div style="text-align:center;margin:20px 15px 5px 15px">
				<table cellspacing="0" cellpadding="10" class="listPerson" style="width: 185mm">
					<thead> <?php $colNum = 5;  if(isset($options['unique'])): $colNum++;endif;if(isset($options['tip'])):$colNum++;endif; $percent = 100/$colNum; ?>
					
						<tr style="color:#ed3389;">
						<th style="width: <?php echo $percent ?>%;padding:0 5px;font-weight: bold"><?php echo t('Name') ?></th>
							<th style="width: <?php echo $percent ?>%;padding:0 5px;font-weight: bold"><?php echo t('Picture')?></th>
							<th style="width: <?php echo $percent ?>%;padding:0 5px;font-weight: bold"><?php echo t('Job title')?></th>
							<th style="width: <?php echo $percent ?>%;padding:0 5px;font-weight: bold">No. <?php echo t('Votes')?></th>
							<?php if(isset($options['unique'])): ?>
							<th style="width: <?php echo $percent ?>%;padding:0 5px;font-weight: bold"> <?php echo t('Unique')?></th>
							<?php endif;?>
							<?php if(isset($options['tip'])): ?>
							<th style="width: <?php echo $percent ?>%;font-weight: bold"><?php echo t('Tips') ?></th>
							<?php endif;?>
							<th style="width: <?php echo $percent ?>%;padding:0 5px;font-weight: bold"><?php echo t('Average') ?></th>
						</tr>
					</thead>
					<tbody style="color:#7d7f81;">
						<?php 
						$people = 1;
						$totalAVG = 0;
						$totalTips = 0;
						$totalVote = 0;
						$totalUnique = 0;
						$employeeReal = 0;
						$totalAmount =0;
						$amount  = 0;
						$count = 0;
						$currency = $place->getCurrencyPlace();
						 foreach($list as $employee): 

						if(!empty($employee)):
							$employeeReal ++;
							$amount = $employee->getAmountCoverted();
							$totalAmount += $amount;
							$totalVote += !empty($employee->totalVote)? $employee->totalVote : 0;
							$totalAVG += !empty($employee->voteAVG)? $employee->voteAVG : 0;
							$totalTips += !empty($employee->total)? $employee->total : 0;
							$totalUnique += !empty($employee->totalUnique)? $employee->totalUnique : 0;
							if($people  >6): 
								echo '		</tbody></table></div>';
								$people = 0;
								echo $this->renderPartial('statics_team_footer');
								echo '<page>';
								echo $this->renderPartial('statics_department_header', array('logo'		 => $logo,'options'=>$options,'department'=>$department,'fr'=>$fr,'to'=>$to,'percent'=>$percent));?>
						
							<?php endif; ?>
						<tr>
							
								<td>
					<?php echo !empty($employee->name)? $employee->name : 'Unknow' ?>
				</td>
				<td>
					<img src="<?php echo $employee->getImage("small")?>" />
				</td>
				
				<td>
					<?php echo !empty($employee->job_title)? $employee->job_title : 'Unknow' ?>
				</td>
				<td>
					<?php echo !empty($employee->totalVote)? $employee->totalVote : '0'?>
				</td>
				<?php if(isset($options['unique'])): ?>
				<td>
					<?php echo !empty($employee->totalUnique)? $employee->totalUnique : '0' ?>
				</td>
				<?php endif;?>
				
				<?php if(isset($options['tip'])): ?>
				<td>
					<?php echo !empty($employee->total)? $employee->getCurrencyFormat() :'0'?>
				</td>
				<?php endif;?>
				<td>
					<?php echo !empty($employee->voteAVG)?$employee->voteAVG:'0'?>
				</td>
				</tr>
						<?php if($employee == end($list) && $endList == true && isset($options['total'])):?>
							<tr>
							
								<td style='border-bottom: 0px'></td>
								<td style='border-bottom: 0px'></td>
								<td style='border-bottom: 0px'>Average</td>
								<td style='border-bottom: 0px'><?php echo number_format($totalVote/$employeeReal,2) ?></td>
								<?php if(isset($options['unique'])): ?>
								<td style='border-bottom: 0px'><?php echo number_format($totalUnique/$employeeReal,2) ?></td>
								<?php endif;?>
								<?php if(isset($options['tip'])): ?>
								<?php $tempAmount =  number_format($totalAmount/$employeeReal,2);
									$avgAmount = StringHelper::Currency($tempAmount, StringHelper::coverToCurrency($currency), '', '0');
								?>
								<td style='border-bottom: 0px'><?php echo $avgAmount  ?></td>
								<?php endif;?>
								<td style='border-bottom: 0px'><?php echo number_format($totalAVG/$employeeReal,2)?></td>
								
				<td style='border-bottom: none'></td>
							</tr>
						<?php endif;?>
						<?php $count ++;$people++;
						endif;
						endforeach; ?>
					</tbody>
				</table>
				
			</div>
			<div style="position:absolute;bottom:10px;right:0px;width: 98%;">
				<table style="text-align: center; vertical-align: middle;width: 677px">
					<tr>

						<td  style=" vertical-align: middle; text-align: center">
							<img width="200" src="<?php echo WWW_URL ?>images/nicepower.png" />

						</td>
					</tr>
					<tr><td style="width: 677px ;text-align: center"><h3 style="color:#ff359c;text-align: center"><?php echo SITE_NAME_URL ?></h3></td></tr>

			</table>
			</div>
			
		</div>
	</div>
<!-- END-->




