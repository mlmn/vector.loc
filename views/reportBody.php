<div class="container">
<table class="table">
	<thead>
		<tr>
			<th>Департамент</th>
			<th>сотр.</th>
			<th>ср. ранг</th>
			<th>тугр.</th>
			<th>кофе</th>
			<th>стр.</th>
			<th>тугр/стр</th>
		</tr>
	</thead>
	<tbody>
		<? foreach ($org->getDepartments() as $dep) { ?>
			<tr>
				<td><?=$dep->getName();?></td>
				<td><?=$dep->countDepEmployees();?></td>
				<td><?=$dep->countAveregeEmployeeRang()?></td>
				<td><?=$dep->countDepSalary();?></td>
				<td><?=$dep->countDepCoffe();?></td>
				<td><?=$dep->countDepPapers();?></td>
				<td><?=$dep->countDepPageCost();?></td>
			</tr>
		<? } ?>
	

			
		<tr>
			<td>Средне</td>
			<td><?=$orgInfo->avgPeople;?></td>
			<td>..</td>
			<td><?=$orgInfo->avgSalary;?></td>
			<td><?=$orgInfo->avgCoffe;?></td>
			<td><?=$orgInfo->avgPapers;?></td>	
			<td><?=$orgInfo->avgCost;?></td>		
		</tr>

		<tr>
			<td>Всего</td>
			<td><?=$orgInfo->totalPeople;?></td>
			<td>..</td>
			<td><?=$orgInfo->totalSalary;?></td>
			<td><?=$orgInfo->totalCoffe;?></td>
			<td><?=$orgInfo->totalPapers;?></td>
			<td><?=$orgInfo->totalCost;?></td>
		</tr>

	</tbody>
</table>
</div>