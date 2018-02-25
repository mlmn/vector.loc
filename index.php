<?php

//Debug class
class Dbg {
	
	//cool dump
	public static function cd($var) {
		echo "<pre>";
		echo "\n";
		var_dump($var);
		exit;
	}
}

class Reporter {
	//ну а почему бы и нет, когда можно сделать красиво)
	static public function pageHeader() {
		echo '<!DOCTYPE html><html lang="en"><head><title>Bootstrap Example</title><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"><script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script><script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script></head><body>';
	}
	static public function pageFooter() {
		echo '</body></html>';
	}

	static public function browserReport(Organisation $org) {
		echo '<div class="container">';
		echo '<table class="table"><thead>';
		echo "<tr>";
		echo "<th>Департамент</th> <th>сотр.</th> <th>тугр.</th> <th>кофе</th> <th>стр.</th> <th>тугр/стр</th>";
		echo "</tr></thead><tbody>";
		foreach ($org->getDepartments() as $dep) {
			echo "<tr>";
			echo "<td>". $dep->getName() . "</td>";
			echo "<td>". $dep->countDepEmployees() . "</td>";
			echo "<td>". $dep->countDepSalary() . "</td>";
			echo "<td>". $dep->countDepCoffe() . "</td>";
			echo "<td>". $dep->countDepPapers() . "</td>";
			echo "<td>". $dep->countDepPageCost() . "</td>";
			echo "</tr>";
		}

		$totals = $org->getOrgTotals();
		
		echo "<tr><td>Средне</td>";
		echo "<td>" . $totals->avgPeople . "</td>";
		echo "<td>" . $totals->avgSalary . "</td>";
		echo "<td>" . $totals->avgCoffe . "</td>";
		echo "<td>" . $totals->avgPapers . "</td>";
		echo "<td>" . $totals->avgCost . "</td>";		
		echo "</tr>";

		echo "<tr><td>Всего</td>";
		echo "<td>" . $totals->totalPeople . "</td>";
		echo "<td>" . $totals->totalSalary . "</td>";
		echo "<td>" . $totals->totalCoffe . "</td>";
		echo "<td>" . $totals->totalPapers . "</td>";
		echo "<td>" . $totals->totalCost . "</td>";		
		echo "</tr>";



		echo "</tbody></table></div>";

	}

	static public function ideoneReport(Organisation $org) {
		echo "<pre>";
		echo "Департамент      сотр.        тугр.           кофе          стр.         тугр/стр.\n";
		foreach ($org->getDepartments() as $dep) {
			echo $dep->getName(). "           " .
				$dep->countDepEmployees(). "         " .
				$dep->countDepSalary(). "          " .			
				$dep->countDepCoffe(). "          " .		
				$dep->countDepPapers(). "          " .			
				$dep->countDepPageCost() . "\n";
		}
		$totals = $org->getOrgTotals();
		echo "\nСредне" . "            " .
		$totals->avgPeople . "     " .
		$totals->avgSalary . "         " .	
		$totals->avgCoffe . "        " .
		$totals->avgPapers . "       " .
		$totals->avgCost . "\n";
		echo "Всего" . "             " .
		$totals->totalPeople . "         " .
		$totals->totalSalary . "        " .	
		$totals->totalCoffe . "        " .
		$totals->totalPapers . "       " .
		$totals->totalCost . "\n";
	}

}

class Organisation {

	private $name;
	private $departments = [];

	public function __construct($name) {
		$this->name = $name;		
	}

	public function addDepartment(Department $dep) {
 
		if (!in_array($dep, $this->departments, true)) {
			$this->departments[] = $dep;
		}
 
	}

	public function getDepartments() {
		return $this->departments;
	}

	public function countDepartments() {
		return count($this->departments);
	}

	public function getOrgTotals() {
		$info = new stdClass();
		$info->totalPeople = 0;
		$info->avgPeople = 0;

		$info->totalSalary = 0;
		$info->avgSalary = 0;

		$info->totalCoffe = 0;
		$info->avgCoffe = 0;

		$info->totalPapers = 0;
		$info->avgPapers = 0;

		$info->totalCost = 0;
		$info->avgCost = 0;

		foreach($this->getDepartments() as $dep) {
			$info->totalPeople += $dep->countDepEmployees();
			$info->totalSalary += $dep->countDepSalary();
			$info->totalCoffe += $dep->countDepCoffe();
			$info->totalPapers += $dep->countDepPapers();
			$info->totalCost += $dep->countDepPageCost();
		}

		$depsCount = $this->countDepartments();

		if ($depsCount > 0) {
			$info->avgPeople = ($info->totalPeople / $depsCount);
			$info->avgSalary = ($info->totalSalary / $depsCount);
			$info->avgCoffe = ($info->totalCoffe / $depsCount);
			$info->avgPapers = ($info->totalPapers / $depsCount);
			$info->avgCost = ($info->totalCost / $depsCount);

		}


		return $info;
	}

}


class Department {

	private $name;
	private $employees = array();

	public function __construct($name) {
		$this->name = $name;
	}

	public function getName() {
		return $this->name;
	}

	public function addEmployee(Employee $employee) {
		if (!in_array($employee, $this->employees, true)) {
			$this->employees[] = $employee;
		}
	}

	public function addEmployees(array $employees) {
		foreach ($employees as $employee) {
			if (is_object($employee) and get_parent_class($employee) == 'Employee') {
				$this->addEmployee($employee);
			}
		}
	}

	public function getEmployees() {
		return $this->employees;
	}

	public function countDepEmployees() {
		return count($this->getEmployees());
	}

	public function countDepCoffe() {
		$depCoffe = 0;
		foreach ($this->getEmployees() as $employee) {
			$depCoffe += $employee->getCoffe();
		}
		return $depCoffe;
	}

	public function countDepPapers() {
		$depPapers = 0;
		foreach ($this->getEmployees() as $employee) {
			$depPapers += $employee->getPapers();
		}
		return $depPapers;
	}

	public function countDepSalary() {
		$depSalary = 0;
		foreach ($this->getEmployees() as $employee) {
			$depSalary += $employee->getSalary();
		}
		return $depSalary;		
	}

	public function countDepPageCost() {
		if ($this->countDepPapers() == 0) {
			return 0;
		} else {
			return ($this->countDepSalary() / $this->countDepPapers());
		}
	}

}

class Names {
	public static function generateFullName() {
		$fLength = mt_rand(2, 6);
		$firstName = self::generateName($fLength);

		$sLength = mt_rand(2, 6);
		$secondName = self::generateName($sLength);

		return $firstName . " " . $secondName;
	}

	private static function generateName($length) {
		$syllables = [
			'а', 'и', 'у', 'о', 'е',
			'на', 'ни','ну', 'но', 'не',
			'ка', 'ки', 'ку', 'ко','ке',
			'та', 'ти', 'ту', 'то', 'те',
			'са', 'си', 'су', 'со', 'се',	
		];
		$name = '';

		for ($i = 1; $i <= $length; $i++) {
			$name .= $syllables[mt_rand(0, count($syllables)-1)];
		}
		$first = mb_strtoupper(mb_substr($name, 0, 1));
		$caseTitleName = $first . mb_substr($name, 1);

		return $caseTitleName;		
	}
}

abstract class Employee {

	private $name;
	private $rang;
	private $leader;
	private $baseSalary;
	private $baseCoffe;
	Private $basePapers;	

	public function __construct($rang, $leader, $name) {
		$this->name = $name;
		$this->rang = $rang;
		$this->leader = $leader;
	}

	public function setBaseSalary($salary) {
		$this->baseSalary = $salary;
	}

	public function setBaseCoffe($coffe) {
		$this->baseCoffe = $coffe;
	}

	public function setBasePapers($papers) {
		$this->basePapers = $papers;
	}

	public function getSalary() {
		if ($this->rang == 1) {
			$rangCoeff = 1;
		} elseif ($this->rang == 2) {
			$rangCoeff = 1.25;
		} elseif ($this->rang == 3) {
			$rangCoeff = 1.5;
		}

		if ($this->leader == true) {
			$leaderCoeff = 1.5;
		} else {
			$leaderCoeff = 1;
		}

		$salary = $this->baseSalary * $rangCoeff * $leaderCoeff;

		return $salary;
	}

	public function getCoffe() {
		if ($this->leader == true) {
			$leaderCoeff = 2;
		} else {
			$leaderCoeff = 1;
		}

		$coffe = $this->baseCoffe * $leaderCoeff;
		return $coffe;
	}

	public function getPapers() {
		if ($this->leader == true) {
			$leaderCoeff = 0;
		} else {
			$leaderCoeff = 1;
		}

		$papers = $this->basePapers * $leaderCoeff;
		return $papers;
	}

}


class Manager extends Employee {
	public function __construct($rang, $leader, $name) {
		parent::__construct($rang, $leader, $name);
		$this->setBaseSalary(500);
		$this->setBaseCoffe(20);
		$this->setBasePapers(200);
	}
}

class Marketer extends Employee {
	public function __construct($rang, $leader, $name) {
		parent::__construct($rang, $leader, $name);
		$this->setBaseSalary(400);
		$this->setBaseCoffe(15);
		$this->setBasePapers(150);
	}
}
class Engineer extends Employee {
	public function __construct($rang, $leader, $name) {
		parent::__construct($rang, $leader, $name);
		$this->setBaseSalary(200);
		$this->setBaseCoffe(5);
		$this->setBasePapers(50);
	}
}
class Analyst extends Employee {
	public function __construct($rang, $leader, $name) {
		parent::__construct($rang, $leader, $name);
		$this->setBaseSalary(800);
		$this->setBaseCoffe(50);
		$this->setBasePapers(5);
	}
}

class PeopleFactory {
	public static function create($class, $rang, $leader, $amount) {
		
		if (get_parent_class($class) == 'Employee') {
			$people = [];
			for ($i = 0; $i < $amount; $i++) {
				$people[] = new $class($rang, $leader, Names::generateFullName());
			}
			return $people;
		}
	}
}

class Crisys {
	public static function prepareDismissal(Organisation $org) {
		$dismissalList = new SplObjectStorage;
		foreach ($org->getDepartments() as $dep) {
			$depList = new StdClass;
			$depList->depName = $dep->getName();
			$depList->dismissalNames = [];
			$dismissalList->attach($depList);
		}

		return $dismissalList;
	}
}


//как более элегантно забить работягами я просто не знаю:
$org = new Organisation('Вектор');

// Департамент закупок: 9×ме1, 3×ме2, 2×ме3, 2×ма1 + руководитель департамента ме2
$dep1 = new Department('Закупок');
$org->addDepartment($dep1);
$dep1->addEmployees(PeopleFactory::create('Manager', 1, false, 9));
$dep1->addEmployees(PeopleFactory::create('Manager', 2, false, 3));
$dep1->addEmployees(PeopleFactory::create('Manager', 3, false, 2));
$dep1->addEmployees(PeopleFactory::create('Marketer', 1, false, 2));
$dep1->addEmployees(PeopleFactory::create('Manager', 2, true, 1));

// Департамент продаж: 12×ме1, 6×ма1, 3×ан1, 2×ан2 + руководитель ма2
$dep2 = new Department('Продаж');
$org->addDepartment($dep2);
$dep2->addEmployees(PeopleFactory::create('Manager', 1, false, 12));
$dep2->addEmployees(PeopleFactory::create('Marketer', 1, false, 6));
$dep2->addEmployees(PeopleFactory::create('Analyst', 1, false, 3));
$dep2->addEmployees(PeopleFactory::create('Analyst', 2, false, 2));
$dep2->addEmployees(PeopleFactory::create('Marketer', 2, true, 1));

// Департамент рекламы: 15×ма1, 10×ма2, 8×ме1, 2×ин1 + руководитель ма3
$dep3 = new Department('Рекламы');
$org->addDepartment($dep3);
$dep3->addEmployees(PeopleFactory::create('Marketer', 1, false, 15));
$dep3->addEmployees(PeopleFactory::create('Marketer', 2, false, 10));
$dep3->addEmployees(PeopleFactory::create('Manager', 1, false, 8));
$dep3->addEmployees(PeopleFactory::create('Engineer', 1, false, 2));
$dep3->addEmployees(PeopleFactory::create('Marketer', 3, true, 1));

// Департамент логистики: 13×ме1, 5×ме2, 5×ин1 + руководитель ме1
$dep4 = new Department('Логистики');
$org->addDepartment($dep4);
$dep4->addEmployees(PeopleFactory::create('Manager', 1, false, 13));
$dep4->addEmployees(PeopleFactory::create('Manager', 2, false, 5));
$dep4->addEmployees(PeopleFactory::create('Engineer', 1, false, 5));
$dep4->addEmployees(PeopleFactory::create('Manager', 1, true, 1));


//Dbg::cd($org);

// $testDep = new Department('Тестовый');
// $testDep->addEmployees(PeopleFactory::create('Manager', 1, false, 2));
// $salary = $testDep->countDepSalary();

// echo $salary;
// //Dbg::cd($testDep);
// foreach ($testDep->getEmployees() as $emp) {
// 	//echo $emp->getSalary();
// 	Dbg::cd($emp);
// }

//разкоментить это если запускать на локалке
// Reporter::pageHeader();
// Reporter::browserReport($org);
// Reporter::pageFooter();

Dbg::cd(Crisys::prepareDismissal($org));



//Reporter::ideoneReport($org);
