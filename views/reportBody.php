<div class="container">
<div class="well">
	<h4><?=hsc($org->getName())?></h4>
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
					<td><?=hsc($dep->getName())?></td>
					<td><?=hsc($dep->countDepEmployees())?></td>
					<td><?=hsc(round($dep->countAveregeEmployeeRang(), 3))?></td>
					<td><?=hsc($dep->countDepSalary())?></td>
					<td><?=hsc($dep->countDepCoffe())?></td>
					<td><?=hsc($dep->countDepPapers())?></td>
					<td><?=hsc(round($dep->countDepPageCost(), 3))?></td>
				</tr>
			<? } ?>
		

				
			<tr>
				<td>Средне</td>
				<td><?=hsc($org->getAvgEmployees())?></td>
				<td>..</td>
				<td><?=hsc($org->getAvgSalary())?></td>
				<td><?=hsc($org->getAvgCoffe())?></td>
				<td><?=hsc($org->getAvgPapers())?></td>	
				<td><?=hsc(round($org->getAvgPageCost(), 3))?></td>		
			</tr>

			<tr>
				<td>Всего</td>
				<td><?=hsc($org->getTotalEmployees())?></td>
				<td>..</td>
				<td><?=hsc($org->getTotalSalary())?></td>
				<td><?=hsc($org->getTotalCoffe())?></td>
				<td><?=hsc($org->getTotalPapers())?></td>
				<td><?=hsc(round($org->getTotalPageCost(), 3))?></td>
			</tr>

		</tbody>
	</table>
</div>
</div>