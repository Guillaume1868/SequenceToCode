<?php

try {
    // Create an instance of Entreprise
    $entreprise = new Entreprise();

    // Add drivers
    $entreprise->addDriver("driver1@example.com");
    $entreprise->addDriver("driver2@example.com");

    // Add vehicles
    $entreprise->addVehicle("CH123456");
    $entreprise->addVehicle("CH654321");
    echo "Init done" . PHP_EOL;

    echo "Attempt to assign a vehicle to a driver" . PHP_EOL;
    $entreprise->assignVehicleToDriver("CH123456", "driver1@example.com");
    echo "Vehicle CH123456 assigned to driver1@example.com" . PHP_EOL;
	
    echo "Attempt to assign another vehicle to the same driver (should throw an exception)" . PHP_EOL;
	try {
		$entreprise->assignVehicleToDriver("CH654321", "driver1@example.com");
	} catch (DriverUnavailableException $e) {
		echo "Success: DriverUnavailableException thrown as expected!" . PHP_EOL;
	}

	echo "Attempt to assign a non existant vehicle to a driver (should throw an exception)" . PHP_EOL;
	try {
		$entreprise->assignVehicleToDriver("CH1", "driver2@example.com");
	} catch (VehicleNotFoundException $e) {
		echo "Success: VehicleNotFoundException thrown as expected!" . PHP_EOL;
	}

	echo "Attempt to assign a vehicle to a non existant driver (should throw an exception)" . PHP_EOL;
	try {
		$entreprise->assignVehicleToDriver("CH654321", "driver3@example.com");
	} catch (DriverNotFoundException $e) {
		echo "Success: DriverNotFoundException thrown as expected!" . PHP_EOL;
	}

	echo "Success: No unexpected exeptions thrown!" . PHP_EOL;

} catch (Exception $e) {
    echo "Unexpected error: " . $e->getMessage() . PHP_EOL;
}


class Entreprise {
	private $drivers;
	private $vehicles;

	public function __construct()
    {
        $this->drivers = [];
		$this->vehicles = [];
    }
	public function assignVehicleToDriver($chassisNumber, $driverEmailaddress) {
		$d = $this->getDriverByEmailaddress($driverEmailaddress);
		$v = $this->getVehicleByChassisNumber($chassisNumber);

		$d->takeAVehicule($v);
	}

	public function addDriver($driverEmailaddress)
    {
		$this->drivers[$driverEmailaddress] = new Driver($driverEmailaddress);
    }

	public function getDriverByEmailaddress($driverEmailaddress): Driver {
		if (!isset($this->drivers[$driverEmailaddress])) {
			throw new DriverNotFoundException();
        }
		return $this->drivers[$driverEmailaddress];
	}

	public function addVehicle($chassisNumber)
    {
		$this->vehicles[$chassisNumber] = new Vehicle($chassisNumber);
    }

	public function getVehicleByChassisNumber($chassisNumber) : Vehicle {
		if (!isset($this->vehicles[$chassisNumber])) {
			throw new VehicleNotFoundException();
        }
		return $this->vehicles[$chassisNumber];
	}
}

class Person {
	public $email;
	
	function __construct($email) {
		$this->email = $email;
	}
}

class Vehicle {
	public $chassisNumber;

	function __construct($chassisNumber) {
		$this->chassisNumber = $chassisNumber;
	}
}

class Driver extends Person {
	private bool $available = true;
	function takeAVehicule(Vehicle $vehicule) : void {
		if (!$this->available)
			throw new DriverUnavailableException();
		$this->available = false;
		//no need to store more data according to sequece diagram.
		//according to client needs, we can store which vehiclue are taken by which driver here
	}
}

class DriverUnavailableException extends Exception {}
class DriverNotFoundException extends Exception {}
class VehicleNotFoundException extends Exception {}
class driverEmailaddressehicleNotFoundException extends Exception {}