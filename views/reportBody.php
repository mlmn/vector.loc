<div class="container">
<div class="well">
	<h4><?=$org->getName() . " " . $org->getTitle()?></h4>
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
					<td><?=$dep->getName()?></td>
					<td><?=$dep->countDepEmployees()?></td>
					<td><?=round($dep->countAveregeEmployeeRang(), 3)?></td>
					<td><?=$dep->countDepSalary()?></td>
					<td><?=$dep->countDepCoffe()?></td>
					<td><?=$dep->countDepPapers()?></td>
					<td><?=round($dep->countDepPageCost(), 3)?></td>
				</tr>
			<? } ?>
		

				
			<tr>
				<td>Средне</td>
				<td><?=$org->getAvgEmployees()?></td>
				<td>..</td>
				<td><?=$org->getAvgSalary()?></td>
				<td><?=$org->getAvgCoffe()?></td>
				<td><?=$org->getAvgPapers()?></td>	
				<td><?=round($org->getAvgPageCost(), 3)?></td>		
			</tr>

			<tr>
				<td>Всего</td>
				<td><?=$org->getTotalEmployees()?></td>
				<td>..</td>
				<td><?=$org->getTotalSalary()?></td>
				<td><?=$org->getTotalCoffe()?></td>
				<td><?=$org->getTotalPapers()?></td>
				<td><?=round($org->getTotalPageCost(), 3)?></td>
			</tr>

		</tbody>
	</table>
</div>
</div>