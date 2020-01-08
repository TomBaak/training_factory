<?php
	
	
	namespace App\classes;
	
	
	use DateTime;
	
	class MaandOmzet
	{
		
		private $date = NULL;
		private $omzet = 0.0;
		
		public function __construct($date, $omzet)
		{
			$this->setDate($date);
			$this->setOmzet($omzet);
		}
		
		public function getDate()
		{
			return $this->date;
		}
		
		public function setDate($date)
		{
			$this->date = $date;
		}
		
		/**
		 * @return float
		 */
		public function getOmzet(): float
		{
			return $this->omzet;
		}
		
		/**
		 * @param float $omzet
		 */
		public function setOmzet(float $omzet): void
		{
			$this->omzet = $omzet;
		}
		
		
		
		
	}