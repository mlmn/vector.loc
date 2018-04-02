<?php
# Задача про компанию «Вектор»
# http://archive-ipq-co.narod.ru/l1/pasta.html
mb_internal_encoding('utf-8');

class Dbg {
	static public function cd($var) {
		echo "<pre>";
		echo "\n";
		print_r($var);
		exit;
	}
}

class Reporter {
	
	public function __construct() {
		include 'views/header.php';
	}
	public function __destruct() {
		include 'views/footer.php';
	}

	public function reportBody(Organisation $org) {
		$orgInfo = $org->getOrgInfo();
		include 'views/reportBody.php';
	}

	public function browserReport(Organisation $org) {
		$this->reportBody($org);
	}
}
class OrgInfo {
	public $totalPeople = 0;
	public $avgPeople = 0;

	public $totalRang = 0;
	public $avgRang = 0;
	
	public $totalSalary = 0;
	public $avgSalary = 0;
	
	public $totalCoffe = 0;
	public $avgCoffe = 0;

	public $totalPapers = 0;
	public $avgPapers = 0;

	public $totalCost = 0;
	public $avgCost = 0;

}


class Organisation {

	private $name;
	private $departments = [];

	public function __construct(string $name) {
		$this->name = $name;		
	}

	public function addDepartment(Department $dep) { 
		if (!in_array($dep, $this->departments, true)) {
			$this->departments[] = $dep;
		} else {
			throw new Exception('Департамент ' . $dep->getName() . ' уже существует.');
		}
 
	}

	public function getDepartments() {
		return $this->departments;
	}

	public function countDepartments() {
		return count($this->departments);
	}

	public function getOrgInfo() {
		$orgInfo = new OrgInfo();

		foreach($this->getDepartments() as $dep) {
			$orgInfo->totalPeople += $dep->countDepEmployees();
			$orgInfo->totalRang += $dep->countAveregeEmployeeRang();
			$orgInfo->totalSalary += $dep->countDepSalary();
			$orgInfo->totalCoffe += $dep->countDepCoffe();
			$orgInfo->totalPapers += $dep->countDepPapers();
			$orgInfo->totalCost += $dep->countDepPageCost();
		}

		$depsCount = $this->countDepartments();

		if ($depsCount > 0) {
			$orgInfo->avgPeople = ($orgInfo->totalPeople / $depsCount);
			$orgInfo->avgRang = ($orgInfo->totalRang / $depsCount);
			$orgInfo->avgSalary = ($orgInfo->totalSalary / $depsCount);
			$orgInfo->avgCoffe = ($orgInfo->totalCoffe / $depsCount);
			$orgInfo->avgPapers = ($orgInfo->totalPapers / $depsCount);
			$orgInfo->avgCost = ($orgInfo->totalCost / $depsCount);
		}
		return $orgInfo;
	}

}


class Department {

	private $name;
	private $employees = array();

	public function __construct(string $name) {
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

	public function fireStuffByName(array $fireList) {
		foreach($this->getEmployees() as $number => $employee) {
			if (in_array($employee->getName(), $fireList)) {
				//var_dump($this->employees);
				unset($this->employees[$number]);
			}
		}
	}
	public function promoteStuffByName(array $promotionList) {
		foreach($this->getEmployees() as $number => $employee) {
			if (in_array($employee->getName(), $promotionList)) {
				$employee->upRang();
			}
		}
	}


	public function getEmployees() {
		return $this->employees;
	}

	public function getAllCertainSpecialists(string $specialist) {
		if (get_parent_class($specialist) != 'Employee') {
			return false;
		}

		$engineersList = [];
		foreach ($this->getEmployees() as $employee) {
			if (get_class($employee) == $specialist) {
				$engineersList[] = $employee;
			}
		}
		return $engineersList;
	}

	public function getTopAnalyst() {
		$topAnalyst = null;
		$topRang = 0;
		foreach ($this->getEmployees() as $employee) {
			if(get_class($employee) == 'Analyst') {
				$rang = $employee->getRang();
				if ($rang > $topRang) {
					$topRang = $rang;
					$topAnalyst = $employee;
				}
			}
		}
		return $topAnalyst;
	}

	public function getLeader() {
		foreach ($this->getEmployees() as $employee) {
			if ($employee->isLeader()) {
				return $employee;
			}
		}
	}

	public function demoteLeader() {
		foreach($this->getEmployees() as $employee) {
			//var_dump($employee->isLeader());
			if ($employee->isLeader() == true) {
				$employee->setLeader(false);
			}
		}
	}

	public function promoteLeaderByName($name) {
		foreach($this->getEmployees() as $employee) {
			if ($employee->getName() == $name) {
				$employee->setLeader(true);
			}
		}
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
			$pageCost = $this->countDepSalary() / $this->countDepPapers();
			
			return round($pageCost, 3);
		}
	}

	public function countAveregeEmployeeRang() {
		$avgRang = 0;
		foreach ($this->getEmployees() as $employee) {
			$avgRang += $employee->getRang();
		}
		$avgRang /= count($this->getEmployees());
		return round($avgRang, 3);
	}


}

class Names {
	public static function generateFullName() {
		$fLength = mt_rand(2, 4);
		$firstName = self::generateName($fLength);

		$sLength = mt_rand(2, 4);
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

	public function setLeader($leader) {
		$this->leader = $leader;
	}

	public function upRang() {
		$this->rang++;
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

	public function getRang() {
		return $this->rang;
	}

	public function isLeader() {
		return $this->leader;
	}

	public function getName() {
		return $this->name;
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

class OrganisationBuilder {
	public $org;
	public $dep;

	private function exceptionEcho($e) {
		//to prevent copypaste this 4 times in function below
		echo 'Выброшено исключение: ',  $e->getMessage(), "\n";
	}

	public function createDefaultVector() {
		$this->org = new Organisation('Вектор');
		
		try {
			// Департамент закупок: 9×ме1, 3×ме2, 2×ме3, 2×ма1 + руководитель департамента ме2
			$this->dep = new Department('Закупок');
			$this->dep->addEmployees(PeopleFactory::create('Manager', 1, false, 9));
			$this->dep->addEmployees(PeopleFactory::create('Manager', 2, false, 3));
			$this->dep->addEmployees(PeopleFactory::create('Manager', 3, false, 2));
			$this->dep->addEmployees(PeopleFactory::create('Marketer', 1, false, 2));
			$this->dep->addEmployees(PeopleFactory::create('Manager', 2, true, 1));		
			$this->org->addDepartment($this->dep);			
		} catch (Exception $e) {
			$this->exceptionEcho($e);
		}

		try {
			// Департамент продаж: 12×ме1, 6×ма1, 3×ан1, 2×ан2 + руководитель ма2
			$this->dep = new Department('Продаж');		
			$this->dep->addEmployees(PeopleFactory::create('Manager', 1, false, 12));
			$this->dep->addEmployees(PeopleFactory::create('Marketer', 1, false, 6));
			$this->dep->addEmployees(PeopleFactory::create('Analyst', 1, false, 3));
			$this->dep->addEmployees(PeopleFactory::create('Analyst', 2, false, 2));
			$this->dep->addEmployees(PeopleFactory::create('Marketer', 2, true, 1));
			$this->org->addDepartment($this->dep);
		} catch (Exception $e) {
			$this->exceptionEcho($e);
		}

		try {
			// Департамент рекламы: 15×ма1, 10×ма2, 8×ме1, 2×ин1 + руководитель ма3
			$this->dep = new Department('Рекламы');		
			$this->dep->addEmployees(PeopleFactory::create('Marketer', 1, false, 15));
			$this->dep->addEmployees(PeopleFactory::create('Marketer', 2, false, 10));
			$this->dep->addEmployees(PeopleFactory::create('Manager', 1, false, 8));
			$this->dep->addEmployees(PeopleFactory::create('Engineer', 1, false, 2));
			$this->dep->addEmployees(PeopleFactory::create('Marketer', 3, true, 1));
			$this->org->addDepartment($this->dep);

		} catch (Exception $e) {
			$this->exceptionEcho($e);
		}

		try {
			// Департамент логистики: 13×ме1, 5×ме2, 5×ин1 + руководитель ме1
			$this->dep = new Department('Логистики');		
			$this->dep->addEmployees(PeopleFactory::create('Manager', 1, false, 13));
			$this->dep->addEmployees(PeopleFactory::create('Manager', 2, false, 5));
			$this->dep->addEmployees(PeopleFactory::create('Engineer', 1, false, 5));
			$this->dep->addEmployees(PeopleFactory::create('Manager', 1, true, 1));
			$this->org->addDepartment($this->dep);
		} catch (Exception $e) {

			$this->exceptionEcho($e);
		}

		return $this->org;
	}
}

class AntiCrisis {
	private $organisation;
	private $departments;

	public function __construct(Organisation $organisation) {
		$this->organisation = $organisation;
		$this->departments = $this->organisation->getDepartments();
	}

	private function prepareFireListOfEngineersInDepartment(Department $dep) {
		$engineersList = $dep->getAllCertainSpecialists('Engineer');
		$needToFire = (int)ceil(count($engineersList)*0.4); //fire 40% of staff round to bigger int
		$fireList = [];
		$rangAvailableToFire = 1;
		$startFire = true;		
		while ($startFire == true and $needToFire > 0) {
			foreach($engineersList as $engineer) {				
				$countFireList = count($fireList);
				if ($needToFire <= $countFireList) {
					$startFire = false;
					break(2);
				}
				if ((!$engineer->isLeader()) and ($engineer->getRang() <= $rangAvailableToFire)) {
					$fireList[] = $engineer->getName();
				}
			}
			$rangAvailableToFire++;
		}
		return $fireList;
	}

	public function firstAntiCrisisMethod() { //fireEngineers
		foreach($this->departments as $dep) {
			$fireList = $this->prepareFireListOfEngineersInDepartment($dep);
			$dep->fireStuffByName($fireList);
		}
	}

	private function boostAnalystsInDepartment(Department $dep) {
		$employees = $dep->getEmployees();
		foreach($employees as $employee) {
			if (get_class($employee) == 'Analyst') {
				$employee->setBaseSalary(1100);
				$employee->setBaseCoffe(75);
			}
		}
	}

	private function makeAnalystLeaderInDepartment(Department $dep) {
		$leader = $dep->getLeader();
		$topAnalyst = $dep->getTopAnalyst();
		if (get_class($leader) != 'Analyst' and $topAnalyst != null) {
			$dep->demoteLeader();
			$dep->promoteLeaderByName($topAnalyst->getName());
		}
	}

	public function secondAntiCrisisMethod() { //boostAnalysts
		foreach($this->departments as $dep) {
			$this->boostAnalystsInDepartment($dep);
			$this->makeAnalystLeaderInDepartment($dep);
		}
	}

	public function preparePromoteListOfManagersInDepartment(Department $dep, array $rangs) {
			$managersList = $dep->getAllCertainSpecialists('Manager');
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
					$promotionList[] = $currentRangManagers[$i]->getName();
				}
			}
			return $promotionList;


	}

	public function thirdAntiCrisisMethod() { //promote 50% of department managers
		$rangsToPromote = array(1, 2);
		foreach ($this->departments as $dep) {
			$promotionList = $this->preparePromoteListOfManagersInDepartment($dep, $rangsToPromote);
			$dep->promoteStuffByName($promotionList);

		}
	}

}

$builder = new OrganisationBuilder();
$org = $builder->createDefaultVector();

$reporter = new Reporter();
$reporter->browserReport($org);

$anti = new AntiCrisis($org);
$anti->thirdAntiCrisisMethod();
$reporter->browserReport($org);
