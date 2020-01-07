<?php
	
	
	namespace App\classes;
	
	
	class MaandOmzet
	{
		
		private $datum = NULL;
		private $omzet = 0.0;
		
		public function __construct($datum, $omzet)
		{
			$this->setDatum($datum);
			$this->setOmzet($omzet);
		}
		
		/**
		 * @return null
		 */
		public function getDatum()
		{
			return $this->datum;
		}
		
		/**
		 * @param null $datum
		 */
		public function setDatum($datum): void
		{
			$this->datum = $datum;
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