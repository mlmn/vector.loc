<?php
# Задача про компанию «Вектор»
# http://archive-ipq-co.narod.ru/l1/pasta.html
mb_internal_encoding('utf-8');
error_reporting(-1);

function hsc($string) {
	return htmlspecialchars($string, ENT_HTML5);
}

class Dbg {
	static public function cd($var) {
		echo "<pre>";
		echo "\n";
		print_r($var);
		exit;
	}
}

class Reporter {
	
	public function browsePageHeader() {
		include 'views/header.php';
	}
	public function browsePageFooter() {
		include 'views/footer.php';
	}
	public function browseCompanyReport(Organisation $org) {
		include 'views/reportBody.php';
	}
}

class Organisation {

	private $name;
	private $title;
	private $departments = [];

	public function __construct(string $name, string $title) {
		$this->name = $name;
		$this->title = $title;	
	}

	public function setTitle(string $title) {
		$this->title = $title;
	}

	public function getTitle(): string {
		return $this->title;
	}
	public function getName(): string {
		return $this->name;
	}

	public function addDepartment(Department $dep) { 
		if (!in_array($dep, $this->departments, true)) {
			$this->departments[] = $dep;
		} else {
			throw new Exception('Департамент ' . $dep->getName() . ' уже существует.');
		}
 
	}

	public function getDepartments(): array {
		if ($this->countDepartments() == 0) {
			throw new Exception('Пустая организация, нечего выводить.');
		} else {
			return $this->departments;
		}
		
	}

	private function countDepartments(): int {
		return count($this->departments);
	}

	public function getTotalEmployees(): int {
		$totalEmployees = 0;
		foreach ($this->getDepartments() as $dep) {
			$totalEmployees += $dep->countDepEmployees();
		}
		return $totalEmployees;
	}

	public function getTotalSalary(): int {
		$totalSalary = 0;
		foreach($this->getDepartments() as $dep) {
			$totalSalary += $dep->countDepSalary();
		}
		return $totalSalary;
	}

	public function getTotalCoffe(): int {
		$totalCoffe = 0;
		foreach($this->getDepartments() as $dep) {
			$totalCoffe += $dep->countDepCoffe();
		}
		return $totalCoffe;
	}
	public function getTotalPapers(): int {
		$totalPapers = 0;
		foreach($this->getDepartments() as $dep) {
			$totalPapers += $dep->countDepPapers();
		}
		return $totalPapers;
	}
	public function getTotalPageCost(): float {
		$totalPageCost = 0;
		foreach($this->getDepartments() as $dep) {
			$totalPageCost += $dep->countDepPageCost();
		}
		return $totalPageCost;
	}

	public function getAvgEmployees():float {
		return $this->getTotalEmployees() / $this->countDepartments();
	}
	public function getAvgSalary(): float {
		return $this->getTotalSalary() / $this->countDepartments();
	}
	public function getAvgCoffe(): float {
		return $this->getTotalCoffe() / $this->countDepartments();
	}
	public function getAvgPapers(): float {
		return $this->getTotalPapers() / $this->countDepartments();
	}
	public function getAvgPageCost(): float {
		return $this->getTotalPageCost() / $this->countDepartments();
	}

}

class EmployeeSelector {
	private $class;
	private $rang = [];
	private $leader = [];

	public function __construct(string $class, array $rang, array $leader) {
		$this->class = $class;
		$this->rang = $rang;
		$this->leader = $leader;
	}
	private function getClass(): string {
		return $this->class;
	}
	private function getRang(): array {
		return $this->rang;
	}
	private function getLeader(): array {
		return $this->leader;
	}

	public function match(Employee $employee): bool {
		$classMatch = (get_class($employee) == $this->getClass());		 	
		$rangMatch = (in_array($employee->getRang(), $this->getRang()));
		$leaderMatch = (in_array($employee->isLeader(), $this->getLeader()));

		if ($classMatch and $rangMatch and $leaderMatch) {
			return true;
		} else {
			return false;
		}
	}

	public function filterEmployees(array $employees): array {
		$filteredEmployees = [];
		foreach ($employees as $employee) {
			if ($this->match($employee)) {
				$filteredEmployees[] = $employee;
			}
		}
		return $filteredEmployees;
	}
}


class Department {

	private $name;
	private $employees = array();

	public function __construct(string $name) {
		$this->name = $name;
	}

	public function getName(): string {
		return $this->name;
	}

	public function addEmployee(Employee $employee) {
		if (!in_array($employee, $this->employees, true)) {
			$this->employees[] = $employee;
		} else {
			throw new Exception('Такой сотрудник уже есть.');
		}
	}

	public function addEmployees(array $employees) {
		foreach ($employees as $employee) {
			if ($employee instanceof Employee) {
				$this->addEmployee($employee);
			} else {
				throw new Exception('Переданный аргумент не является объектом класса Employee.');
			}
		}
	}

	public function fireStuff(array $fireList) {
		foreach($this->getEmployees() as $number => $employee) {
			if (in_array($employee, $fireList)) {
				unset($this->employees[$number]);
			}
		}
	}

	public function getEmployees(): array {
		return $this->employees;
	}


	public function getLeader(): Employee {
		foreach ($this->getEmployees() as $employee) {
			if ($employee->isLeader()) {
				return $employee;
			}
		}
	}

	private function demoteLeader() {
		foreach($this->getEmployees() as $employee) {
			if ($employee->isLeader() == true) {
				$employee->setLeader(false);
			}
		}
	}

	private function promoteLeader(Employee $newLeader) {
		foreach($this->getEmployees() as $employee) {
			if ($employee == $newLeader) {
				$employee->setLeader(true);
			}
		}
	}

	public function swapLeader(Employee $newLeader) {
		$this->demoteLeader();
		$this->promoteLeader($newLeader);
	}

	public function countDepEmployees(): int {
		return count($this->getEmployees());
	}

	public function countDepCoffe(): int {
		$depCoffe = 0;
		foreach ($this->getEmployees() as $employee) {
			$depCoffe += $employee->getCoffe();
		}
		return $depCoffe;
	}

	public function countDepPapers(): int {
		$depPapers = 0;
		foreach ($this->getEmployees() as $employee) {
			$depPapers += $employee->getPapers();
		}
		return $depPapers;
	}

	public function countDepSalary(): float {
		$depSalary = 0;
		foreach ($this->getEmployees() as $employee) {
			$depSalary += $employee->getSalary();
		}
		return $depSalary;		
	}

	public function countDepPageCost(): float {
		if ($this->countDepPapers() == 0) {
			throw new Exception('Деление на 0');
		} else {
			return $this->countDepSalary() / $this->countDepPapers();
		}
	}

	public function countAveregeEmployeeRang(): float {
		$avgRang = 0;
		foreach ($this->getEmployees() as $employee) {
			$avgRang += $employee->getRang();
		}
		$avgRang /= count($this->getEmployees());
		return $avgRang;
	}


}

class NameGenerator {
	public static function generateFullName(): string {
		$fLength = mt_rand(2, 4);
		$firstName = self::generateName($fLength);

		$sLength = mt_rand(2, 4);
		$secondName = self::generateName($sLength);

		return $firstName . " " . $secondName;
	}

	private static function generateName($length): string {
		$syllables = [
			'а', 'и', 'у', 'о', 'е',
			'на', 'ни','ну', 'но', 'не',
			'ка', 'ки', 'ку', 'ко','ке',
			'та', 'ти', 'ту', 'то', 'те',
			'са', 'си', 'су', 'со', 'се',
			'ма', 'ми', 'му', 'мо', 'ме',
			'ра', 'ри', 'ру', 'ро', 'ре',
			'ха', 'хи', 'ху', 'хо', 'хе',
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
		$this->setDefaults();
	}

	abstract protected function setDefaults();

	public function setBaseSalary($salary) {
		$this->baseSalary = $salary;
	}

	public function setBaseCoffe($coffe) {
		$this->baseCoffe = $coffe;
	}

	public function setBasePapers($papers) {
		$this->basePapers = $papers;
	}

	public function setLeader($leader) {
		$this->leader = $leader;
	}

	public function upRang() {
		if ($this->rang < 3) {
			$this->rang++;
		}
		
	}

	public function getSalary(): float {
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

	public function getCoffe(): int {
		if ($this->leader == true) {
			$leaderCoeff = 2;
		} else {
			$leaderCoeff = 1;
		}

		$coffe = $this->baseCoffe * $leaderCoeff;
		return $coffe;
	}

	public function getPapers(): int {
		if ($this->leader == true) {
			$leaderCoeff = 0;
		} else {
			$leaderCoeff = 1;
		}

		$papers = $this->basePapers * $leaderCoeff;
		return $papers;
	}

	public function getRang(): int {
		return $this->rang;
	}

	public function isLeader(): bool {
		return $this->leader;
	}

	public function getName(): string {
		return $this->name;
	}
}


class Manager extends Employee {
	protected function setDefaults() {
		$this->setBaseSalary(500);
		$this->setBaseCoffe(20);
		$this->setBasePapers(200);
	}
}

class Marketer extends Employee {
	protected function setDefaults() {
		$this->setBaseSalary(400);
		$this->setBaseCoffe(15);
		$this->setBasePapers(150);
	}
}

class Engineer extends Employee {
	protected function setDefaults() {
		$this->setBaseSalary(200);
		$this->setBaseCoffe(5);
		$this->setBasePapers(50);
	}
}

class Analyst extends Employee {
	protected function setDefaults() {
		$this->setBaseSalary(800);
		$this->setBaseCoffe(50);
		$this->setBasePapers(5);
	}
}

class PeopleFactory {
	public static function create($class, $rang, $leader, $amount) {		
		if (is_subclass_of($class, 'Employee')) {
			$people = [];
			for ($i = 0; $i < $amount; $i++) {
				$people[] = new $class($rang, $leader, NameGenerator::generateFullName());
			}
			return $people;
		}
	}
}

class OrganisationBuilder {

	public function createDefaultVector() {
		$org = new Organisation('Вектор', 'ванильный');
		
		// Департамент закупок: 9×ме1, 3×ме2, 2×ме3, 2×ма1 + руководитель департамента ме2
		$department1 = new Department('Закупок');

		$department1->addEmployees(PeopleFactory::create('Manager', 1, false, 9));
		$department1->addEmployees(PeopleFactory::create('Manager', 2, false, 3));						
		$department1->addEmployees(PeopleFactory::create('Manager', 3, false, 2));
		$department1->addEmployees(PeopleFactory::create('Marketer', 1, false, 2));
		$department1->addEmployees(PeopleFactory::create('Manager', 2, true, 1));		
		$org->addDepartment($department1);			

		// Департамент продаж: 12×ме1, 6×ма1, 3×ан1, 2×ан2 + руководитель ма2
		$department2 = new Department('Продаж');		
		$department2->addEmployees(PeopleFactory::create('Manager', 1, false, 12));
		$department2->addEmployees(PeopleFactory::create('Marketer', 1, false, 6));
		$department2->addEmployees(PeopleFactory::create('Analyst', 1, false, 3));
		$department2->addEmployees(PeopleFactory::create('Analyst', 2, false, 2));
		$department2->addEmployees(PeopleFactory::create('Marketer', 2, true, 1));
		$org->addDepartment($department2);


		// Департамент рекламы: 15×ма1, 10×ма2, 8×ме1, 2×ин1 + руководитель ма3
		$department3 = new Department('Рекламы');		
		$department3->addEmployees(PeopleFactory::create('Marketer', 1, false, 15));
		$department3->addEmployees(PeopleFactory::create('Marketer', 2, false, 10));
		$department3->addEmployees(PeopleFactory::create('Manager', 1, false, 8));
		$department3->addEmployees(PeopleFactory::create('Engineer', 1, false, 2));
		$department3->addEmployees(PeopleFactory::create('Marketer', 3, true, 1));
		$org->addDepartment($department3);


		// Департамент логистики: 13×ме1, 5×ме2, 5×ин1 + руководитель ме1
		$department4 = new Department('Логистики');		
		$department4->addEmployees(PeopleFactory::create('Manager', 1, false, 13));
		$department4->addEmployees(PeopleFactory::create('Manager', 2, false, 5));
		$department4->addEmployees(PeopleFactory::create('Engineer', 1, false, 5));
		$department4->addEmployees(PeopleFactory::create('Manager', 1, true, 1));
		$org->addDepartment($department4);

		return $org;
	}
}

class AntiCrisis {
	private $organisation;

	public function __construct(Organisation $organisation) {
		$this->organisation = $organisation;
	}

	private function prepareEngineersForFire(Department $dep) {
		$employeeSelector = new EmployeeSelector('Engineer', [1, 2, 3], [true, false]);
		
		$engineersList = $employeeSelector->filterEmployees($dep->getEmployees());
		$needToFire = (int)ceil(count($engineersList)*0.4); //fire 40% of staff round to bigger int

		//sorting employees by rang;
		usort($engineersList, function($a, $b) {
			if ($a->getRang() == $b->getRang()) {
				return 0;
			}
			return ($a->getRang() < $b->getRang()) ? -1 : 1;
		});

		//then sorting by leader, because leader is always last to fire;
		usort($engineersList, function($a, $b) {
			if ($a->isLeader() == $b->isLeader()) {
				return 0;
			}
			if ($a->isLeader() == false and $b->isLeader() == true) {
				return -1;
			} else {
				return 1;
			}
		});
		$fireList = array_slice($engineersList, 0, $needToFire);

		return $fireList;
	}

	public function firstAntiCrisisMethod() { //fireEngineers
		foreach($this->organisation->getDepartments() as $dep) {
			$fireList = $this->prepareEngineersForFire($dep);
			$dep->fireStuff($fireList);
		}

		$this->organisation->setTitle("после антикризисных мер #1"); 
	}

	private function boostAnalystsInDepartment(Department $dep) {
		$employees = $dep->getEmployees();
		foreach($employees as $employee) {
			if ($employee instanceof Analyst) {
				$employee->setBaseSalary(1100);
				$employee->setBaseCoffe(75);
			}
		}
	}

	private function getTopAnalyst(array $employees): ?Analyst {
		$topAnalyst = null;
		$topRang = 0;
		foreach ($employees as $employee) {
			if ($employee instanceof Analyst) {
				$rang = $employee->getRang();
				if ($rang > $topRang) {
					$topRang = $rang;
					$topAnalyst = $employee;
				}			
			}
		}
		return $topAnalyst;
	}

	private function makeAnalystLeaderInDepartment(Department $dep) {
		$leader = $dep->getLeader();
		$topAnalyst = $this->getTopAnalyst($dep->getEmployees());
		if (!($leader instanceof Analyst) and $topAnalyst != null) {
			$dep->swapLeader($topAnalyst);
		}
	}

	public function secondAntiCrisisMethod() { //boostAnalysts
		foreach($this->organisation->getDepartments() as $dep) {
			$this->boostAnalystsInDepartment($dep);
			$this->makeAnalystLeaderInDepartment($dep);
		}
		$this->organisation->setTitle("после антикризисных мер #2"); 
	}

	public function preparePromoteListOfManagersInDepartment(Department $dep, array $rangs) {		
		$employeeSelector = new EmployeeSelector('Manager', array(1, 2, 3), array(true, false));
		
		$managersList = $employeeSelector->filterEmployees($dep->getEmployees());
		$totalAvailableToPromote = 0;
		$managersOfSertainRangs = [];
		foreach ($managersList as $manager) {
			$currentRang = $manager->getRang();
			if (in_array($currentRang, $rangs)) {
				$totalAvailableToPromote++;
				$managersOfSertainRangs[$currentRang][] = $manager;
			}
		}
		$promotionList = [];
		foreach ($managersOfSertainRangs as $currentRangManagers) {
			$thisRangToPromote = count($currentRangManagers);
			$neededToPromote = ceil(0.5 * ($thisRangToPromote));

			for ($i = 0; $i < $neededToPromote; $i++) {
				$currentRangManagers[$i]->upRang();
			}
		}
	}

	public function thirdAntiCrisisMethod() { //promote 50% of department managers
		$rangsToPromote = array(1, 2);
		foreach ($this->organisation->getDepartments() as $dep) {
			$this->preparePromoteListOfManagersInDepartment($dep, $rangsToPromote);

		}
		$this->organisation->setTitle("после антикризисных мер #3"); 
	}

}

$builder = new OrganisationBuilder();
$vectorVanilla = $builder->createDefaultVector();

$reporter = new Reporter();
$reporter->browsePageHeader();
$reporter->browseCompanyReport($vectorVanilla);

$vectorFirstAC = clone $vectorVanilla;
$anti = new AntiCrisis($vectorFirstAC);
$anti->firstAntiCrisisMethod();
$reporter->browseCompanyReport($vectorFirstAC);

$vectorSecondAC = clone $vectorVanilla;
$anti = new AntiCrisis($vectorSecondAC);
$anti->secondAntiCrisisMethod();
$reporter->browseCompanyReport($vectorSecondAC);

$vectorThirdAC = clone $vectorVanilla;
$anti = new AntiCrisis($vectorThirdAC);
$anti->thirdAntiCrisisMethod();
$reporter->browseCompanyReport($vectorThirdAC);

$reporter->browsePageFooter();